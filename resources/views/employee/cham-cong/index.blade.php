{{-- resources/views/employee/cham-cong/index.blade.php --}}
@extends('layouts.employee')

@section('title', 'Chấm công')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-clock mr-3 text-blue-600 dark:text-blue-400"></i>
                    Chấm công
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Check-in / Check-out tự động xác định ca làm việc</p>
            </div>
            <span class="text-sm text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-3 py-1.5 rounded-lg">
                <i class="far fa-calendar-alt mr-1.5"></i> {{ Carbon\Carbon::now()->format('d/m/Y') }}
            </span>
        </div>

        <!-- ===== THÔNG BÁO VỊ TRÍ ===== -->
        @if (!$isValidLocation)
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4">
                <div class="flex items-start">
                    <i class="fas fa-times-circle text-red-600 dark:text-red-400 mt-0.5"></i>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800 dark:text-red-300">
                            ❌ Vị trí không hợp lệ - Không thể chấm công
                        </p>
                        <p class="text-xs text-red-700 dark:text-red-400/70 mt-1">
                            IP hiện tại: <strong>{{ $currentIP }}</strong>
                            @if ($currentWiFi)
                                , WiFi: <strong>{{ $currentWiFi }}</strong>
                            @else
                                , WiFi: <strong>📡 Chưa kết nối</strong>
                            @endif
                        </p>
                        <p class="text-xs text-red-600 dark:text-red-400 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Vui lòng kết nối WiFi công ty hoặc sử dụng IP được phép.
                        </p>
                        <p class="text-xs text-green-600 dark:text-green-400 mt-1">
                            📡 IP được phép: <strong>{{ implode(', ', $dsIP) }}</strong>
                        </p>
                        <p class="text-xs text-green-600 dark:text-green-400 mt-1">
                            📶 WiFi được phép: <strong>{{ implode(', ', $dsWiFi) }}</strong>
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4">
                <div class="flex items-start">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400 mt-0.5"></i>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800 dark:text-green-300">
                            ✅ Vị trí hợp lệ - Sẵn sàng chấm công
                        </p>
                        <p class="text-xs text-green-700 dark:text-green-400/70 mt-1">
                            IP: <strong>{{ $currentIP }}</strong>
                            @if ($currentWiFi)
                                , WiFi: <strong>{{ $currentWiFi }}</strong>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- ===== MAIN CHẤM CÔNG ===== -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900 dark:text-white">
                    <i class="fas fa-fingerprint mr-2 text-blue-600 dark:text-blue-400"></i>
                    Trạng thái chấm công
                </h3>
                <span class="text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2.5 py-1 rounded-full">
                    Hôm nay
                </span>
            </div>
            <div class="p-6">
                <!-- Thông tin ca hiện tại -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Ca làm việc hiện tại</p>
                        @if($caHienTai)
                            <p class="text-xl font-bold text-blue-600 dark:text-blue-400">
                                {{ $caHienTai->ten }}
                                <span class="text-sm font-normal text-gray-500">
                                    ({{ Carbon\Carbon::parse($caHienTai->gio_bat_dau)->format('H:i') }} - {{ Carbon\Carbon::parse($caHienTai->gio_ket_thuc)->format('H:i') }})
                                </span>
                            </p>
                        @else
                            <p class="text-xl font-bold text-gray-400">Ngoài giờ làm việc</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Thời gian hiện tại</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white" id="current-time">
                            {{ Carbon\Carbon::now('Asia/Ho_Chi_Minh')->format('H:i:s') }}
                        </p>
                    </div>
                </div>

                <!-- Trạng thái -->
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 text-center">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Check-in</p>
                        @if($daCheckIn)
                            <p class="text-green-600 font-bold text-lg">{{ $chamCongHomNay->gio_vao_format }}</p>
                            @if($chamCongHomNay->phut_di_muon > 0)
                                <p class="text-xs text-yellow-600">(+{{ $chamCongHomNay->phut_di_muon }}p)</p>
                            @endif
                            <p class="text-xs text-gray-400">Ca: {{ $chamCongHomNay->ten_ca }}</p>
                        @else
                            <p class="text-gray-400 text-lg">--:--</p>
                            <p class="text-xs text-gray-400">Chưa check-in</p>
                        @endif
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 text-center">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Check-out</p>
                        @if($daCheckOut)
                            <p class="text-red-600 font-bold text-lg">{{ $chamCongHomNay->gio_ra_format }}</p>
                            @if($chamCongHomNay->phut_ve_som > 0)
                                <p class="text-xs text-yellow-600">(-{{ $chamCongHomNay->phut_ve_som }}p)</p>
                            @endif
                            @if($chamCongHomNay->ly_do_ve_som)
                                <p class="text-xs text-gray-500 truncate">Lý do: {{ $chamCongHomNay->ly_do_ve_som }}</p>
                            @endif
                        @else
                            <p class="text-gray-400 text-lg">--:--</p>
                            <p class="text-xs text-gray-400">Chưa check-out</p>
                        @endif
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 text-center">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Trạng thái</p>
                        @if($daCheckIn && $daCheckOut)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-700">
                                <i class="fas fa-check-circle mr-1"></i> Hoàn thành
                            </span>
                            <p class="text-xs text-gray-400 mt-1">{{ number_format($chamCongHomNay->so_gio_lam ?? 0, 1) }}h -
                                {{ number_format($chamCongHomNay->so_cong ?? 0, 2) }} công</p>
                        @elseif($daCheckIn)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-700">
                                <i class="fas fa-clock mr-1"></i> Đang làm
                            </span>
                            <p class="text-xs text-gray-400 mt-1">Đã làm
                                {{ number_format($chamCongHomNay->so_gio_lam ?? 0, 1) }}h</p>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-500">
                                <i class="fas fa-hourglass-start mr-1"></i> Chưa bắt đầu
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Nút chấm công -->
                <div class="flex flex-col items-center">
                    @if(!$daCheckIn)
                        <!-- Chưa check-in -->
                        <button onclick="handleCheckIn()" id="btnCheckIn"
                                class="px-12 py-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all duration-300 shadow-lg shadow-blue-600/30 hover:shadow-blue-600/50 flex items-center justify-center"
                                {{ !$isValidLocation ? 'disabled' : '' }}>
                            <i class="fas fa-sign-in-alt mr-3 text-xl"></i>
                            <span>Check-in</span>
                        </button>
                        @if(!$isValidLocation)
                            <p class="text-xs text-red-500 dark:text-red-400 mt-2">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                Vui lòng kết nối WiFi công ty hoặc sử dụng IP được phép
                            </p>
                        @endif
                    @elseif(!$daCheckOut)
                        <!-- Đã check-in, chưa check-out -->
                        <button onclick="handleCheckOut()" id="btnCheckOut"
                                class="px-12 py-4 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-xl transition-all duration-300 shadow-lg shadow-orange-500/30 hover:shadow-orange-500/50 flex items-center justify-center"
                                {{ !$isValidLocation ? 'disabled' : '' }}>
                            <i class="fas fa-sign-out-alt mr-3 text-xl"></i>
                            <span>Check-out</span>
                        </button>
                        @if(!$isValidLocation)
                            <p class="text-xs text-red-500 dark:text-red-400 mt-2">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                Vui lòng ở trong công ty để check-out
                            </p>
                        @endif
                    @else
                        <!-- Đã hoàn thành -->
                        <div class="text-center">
                            <div class="inline-flex items-center px-6 py-3 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-xl">
                                <i class="fas fa-check-circle text-2xl mr-3"></i>
                                <div>
                                    <p class="font-bold">Đã hoàn thành chấm công hôm nay</p>
                                    <p class="text-sm">Đã làm {{ number_format($chamCongHomNay->so_gio_lam ?? 0, 1) }} giờ</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- ===== THÔNG TIN VỊ TRÍ ===== -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- IP -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border 
                        {{ $ipStatus == 'valid' ? 'border-green-500 ring-2 ring-green-500/20' : ($ipStatus == 'invalid' ? 'border-red-300' : 'border-gray-100 dark:border-gray-700') }} 
                        p-4 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-network-wired {{ $ipStatus == 'valid' ? 'text-green-600 dark:text-green-400' : ($ipStatus == 'invalid' ? 'text-red-500 dark:text-red-400' : 'text-gray-400') }} w-5"></i>
                        <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">IP hiện tại</span>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-medium {{ $ipStatus == 'valid' ? 'text-green-600 dark:text-green-400 font-bold' : ($ipStatus == 'invalid' ? 'text-red-600 dark:text-red-400' : 'text-gray-400') }}">
                            {{ $currentIP }}
                        </span>
                        <span class="block text-xs">
                            @if($ipStatus == 'valid')
                                <span class="text-green-600 dark:text-green-400 font-medium">✅ Hợp lệ</span>
                            @elseif($ipStatus == 'invalid')
                                <span class="text-red-500 dark:text-red-400 font-medium">❌ Không hợp lệ</span>
                            @else
                                <span class="text-gray-400">Chưa xác định</span>
                            @endif
                        </span>
                    </div>
                </div>
                @if(count($dsIP) > 0)
                    <div class="mt-2 pt-2 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-[10px] text-gray-400">📡 IP được phép: {{ implode(', ', $dsIP) }}</p>
                    </div>
                @endif
            </div>

            <!-- WiFi -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border 
                        {{ $wifiStatus == 'valid' ? 'border-green-500 ring-2 ring-green-500/20' : ($wifiStatus == 'invalid' ? 'border-red-300' : 'border-gray-100 dark:border-gray-700') }} 
                        p-4 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-wifi {{ $wifiStatus == 'valid' ? 'text-green-600 dark:text-green-400' : ($wifiStatus == 'invalid' ? 'text-red-500 dark:text-red-400' : 'text-gray-400') }} w-5"></i>
                        <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">WiFi hiện tại</span>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-medium {{ $wifiStatus == 'valid' ? 'text-green-600 dark:text-green-400 font-bold' : ($wifiStatus == 'invalid' ? 'text-red-600 dark:text-red-400' : 'text-gray-400') }}" id="wifi-display">
                            {{ $currentWiFi ?: '📡 Chưa kết nối' }}
                        </span>
                        <span class="block text-xs" id="wifi-status-text">
                            @if($wifiStatus == 'valid')
                                <span class="text-green-600 dark:text-green-400 font-medium">✅ Hợp lệ</span>
                            @elseif($wifiStatus == 'invalid')
                                <span class="text-red-500 dark:text-red-400 font-medium">❌ Không hợp lệ</span>
                            @else
                                <span class="text-gray-400">📡 Chưa kết nối</span>
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Danh sách WiFi được phép -->
                @if(count($dsWiFi) > 0)
                    <div class="mt-2 pt-2 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-[10px] text-gray-400">📶 WiFi được phép: {{ implode(', ', $dsWiFi) }}</p>
                        @if($wifiStatus == 'invalid' && $currentWiFi)
                            <p class="text-[10px] text-red-500 mt-1">⚠️ WiFi "{{ $currentWiFi }}" không có trong danh sách được phép</p>
                        @endif
                    </div>
                @endif

                <!-- Trạng thái kết nối -->
                <div class="mt-2 pt-2 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500 dark:text-gray-400">Trạng thái:</span>
                        @if($wifiStatus == 'valid')
                            <span class="inline-flex items-center gap-1.5 text-xs font-medium text-green-600 dark:text-green-400">
                                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                ✅ Đã kết nối hợp lệ
                            </span>
                        @elseif($wifiStatus == 'invalid')
                            <span class="inline-flex items-center gap-1.5 text-xs font-medium text-red-600 dark:text-red-400">
                                <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                ❌ Không hợp lệ
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-400">
                                <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                                📡 Chưa kết nối
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== LỊCH SỬ 7 NGÀY ===== -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900 dark:text-white">
                    <i class="fas fa-history mr-2 text-blue-600 dark:text-blue-400"></i>
                    Lịch sử 7 ngày gần nhất
                </h3>
                <a href="{{ route('employee.cham-cong.history') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                    Xem tất cả <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Ngày</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Ca</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Check-in</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Check-out</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Công</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($lichSu as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    {{ $item->ngay_cham_cong_format }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-2 py-1 rounded text-xs {{ $item->caLamViec && $item->caLamViec->ten == 'Sáng' ? 'bg-yellow-100 text-yellow-700' : 'bg-indigo-100 text-indigo-700' }}">
                                        {{ $item->ten_ca }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    {{ $item->gio_vao_format }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    {{ $item->gio_ra_format }}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-blue-600">
                                    {{ number_format($item->so_cong ?? 0, 2) }}
                                </td>
                                <td class="px-6 py-4">
                                    @include('employee.cham-cong.partials.status-badge', ['status' => $item->trang_thai])
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-inbox text-2xl block mb-2 text-gray-300 dark:text-gray-600"></i>
                                    Chưa có dữ liệu chấm công
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ===== MODAL NHẬP LÝ DO VỀ SỚM ===== -->
    <div id="modal-ve-som" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md p-6 mx-4">
            <h3 class="text-xl font-bold mb-4">⚠️ Bạn đang về sớm</h3>
            <p class="text-gray-600 dark:text-gray-300 mb-2">
                Bạn đang check-out sớm <span id="phut-ve-som-text" class="font-bold text-yellow-600">0</span> phút.
            </p>
            <p class="text-gray-600 dark:text-gray-300 mb-4">Vui lòng nhập lý do để hoàn tất check-out.</p>
            <div class="mb-4">
                <label class="block font-medium mb-2">Lý do về sớm</label>
                <textarea id="ly-do-ve-som" rows="3"
                          class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600"
                          placeholder="Nhập lý do..."></textarea>
            </div>
            <div class="flex gap-3 justify-end">
                <button onclick="closeModalVeSom()" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">
                    Hủy
                </button>
                <button onclick="xacNhanVeSom()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Xác nhận check-out
                </button>
            </div>
        </div>
    </div>

    <!-- ===== FORM ẨN ===== -->
    <form id="checkInForm" action="{{ route('employee.cham-cong.check-in') }}" method="POST" style="display:none;">
        @csrf
        <input type="hidden" name="client_time" id="checkInClientTime">
    </form>

    <form id="checkOutForm" action="{{ route('employee.cham-cong.check-out') }}" method="POST" style="display:none;">
        @csrf
        <input type="hidden" name="client_time" id="checkOutClientTime">
        <input type="hidden" name="ly_do_ve_som" id="checkOutLyDo">
    </form>

@endsection

@push('scripts')
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
    
    const timeElement = document.getElementById('current-time');
    if (timeElement) {
        timeElement.textContent = timeString;
    }
}

setInterval(updateClock, 1000);
updateClock();

// =============================================
// THÔNG TIN THIẾT BỊ
// =============================================

function getWiFiSSID() {
    let saved = localStorage.getItem('wifi_ssid');
    if (saved) return saved;
    let session = sessionStorage.getItem('wifi_ssid');
    if (session) return session;
    return null;
}

function getMACAddress() {
    let saved = localStorage.getItem('mac_address');
    if (saved) return saved;
    return 'AA:BB:CC:DD:EE:01';
}

// =============================================
// THỜI GIAN
// =============================================

function getCurrentTimeISO() {
    return new Date().toISOString();
}

// =============================================
// CHECK-IN
// =============================================

function handleCheckIn() {
    const btn = document.getElementById('btnCheckIn');
    if (!btn) return;

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Đang xử lý...';

    document.getElementById('checkInClientTime').value = getCurrentTimeISO();

    const form = document.getElementById('checkInForm');
    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-WiFi-SSID': getWiFiSSID(),
            'X-MAC-Address': getMACAddress()
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', data.message);
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification('error', data.message || 'Có lỗi xảy ra');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-sign-in-alt mr-3 text-xl"></i><span>Check-in</span>';
        }
    })
    .catch(error => {
        showNotification('error', 'Lỗi: ' + error.message);
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-sign-in-alt mr-3 text-xl"></i><span>Check-in</span>';
    });
}

// =============================================
// CHECK-OUT
// =============================================

function handleCheckOut() {
    const btn = document.getElementById('btnCheckOut');
    if (!btn) return;

    fetch('{{ route("employee.cham-cong.trang-thai") }}')
        .then(res => res.json())
        .then(status => {
            const now = new Date();
            const gio = now.getHours();
            const phut = now.getMinutes();
            
            if (status.ca === 'Sáng') {
                if (gio < 12 || (gio === 12 && phut <= 0)) {
                    const phutVeSom = (12 - gio) * 60 - phut;
                    document.getElementById('phut-ve-som-text').textContent = phutVeSom;
                    document.getElementById('modal-ve-som').classList.remove('hidden');
                    document.getElementById('modal-ve-som').classList.add('flex');
                    return;
                }
            } else if (status.ca === 'Chiều') {
                if (gio < 17 || (gio === 17 && phut <= 30)) {
                    const phutVeSom = (17 - gio) * 60 + (30 - phut);
                    document.getElementById('phut-ve-som-text').textContent = phutVeSom;
                    document.getElementById('modal-ve-som').classList.remove('hidden');
                    document.getElementById('modal-ve-som').classList.add('flex');
                    return;
                }
            }

            thucHienCheckOut();
        })
        .catch(() => {
            thucHienCheckOut();
        });
}

function xacNhanVeSom() {
    const lyDo = document.getElementById('ly-do-ve-som').value.trim();
    if (!lyDo) {
        showNotification('warning', 'Vui lòng nhập lý do về sớm!');
        return;
    }

    closeModalVeSom();
    document.getElementById('checkOutLyDo').value = lyDo;
    thucHienCheckOut();
}

function closeModalVeSom() {
    document.getElementById('modal-ve-som').classList.add('hidden');
    document.getElementById('modal-ve-som').classList.remove('flex');
    document.getElementById('ly-do-ve-som').value = '';
}

function thucHienCheckOut() {
    const btn = document.getElementById('btnCheckOut');
    if (!btn) return;

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Đang xử lý...';

    document.getElementById('checkOutClientTime').value = getCurrentTimeISO();

    const form = document.getElementById('checkOutForm');
    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-WiFi-SSID': getWiFiSSID(),
            'X-MAC-Address': getMACAddress()
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', data.message);
            setTimeout(() => location.reload(), 1500);
        } else if (data.yeu_cau_ly_do) {
            document.getElementById('phut-ve-som-text').textContent = data.phut_ve_som || 0;
            document.getElementById('modal-ve-som').classList.remove('hidden');
            document.getElementById('modal-ve-som').classList.add('flex');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-sign-out-alt mr-3 text-xl"></i><span>Check-out</span>';
        } else {
            showNotification('error', data.message || 'Có lỗi xảy ra');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-sign-out-alt mr-3 text-xl"></i><span>Check-out</span>';
        }
    })
    .catch(error => {
        showNotification('error', 'Lỗi: ' + error.message);
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-sign-out-alt mr-3 text-xl"></i><span>Check-out</span>';
    });
}

// =============================================
// THÔNG BÁO
// =============================================

function showNotification(type, message) {
    const colors = {
        success: 'bg-green-50 dark:bg-green-900/30 border-green-200 text-green-800',
        error: 'bg-red-50 dark:bg-red-900/30 border-red-200 text-red-800',
        warning: 'bg-yellow-50 dark:bg-yellow-900/30 border-yellow-200 text-yellow-800'
    };
    const icons = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        warning: 'fa-exclamation-triangle'
    };

    const el = document.createElement('div');
    el.className = `fixed top-4 right-4 z-50 max-w-md w-full ${colors[type]} border rounded-xl p-4 shadow-lg transform transition-all duration-300 translate-x-full opacity-0`;
    el.innerHTML = `
        <div class="flex items-start">
            <i class="fas ${icons[type]} text-lg mt-0.5"></i>
            <div class="ml-3"><p class="text-sm font-medium">${message}</p></div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    document.body.appendChild(el);
    setTimeout(() => el.classList.remove('translate-x-full', 'opacity-0'), 100);
    setTimeout(() => {
        el.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => el.remove(), 300);
    }, 4000);
}
</script>
@endpush