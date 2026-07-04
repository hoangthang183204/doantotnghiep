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
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Check-in / Check-out với IP/WiFi công ty</p>
            </div>
            <span class="text-sm text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-3 py-1.5 rounded-lg">
                <i class="far fa-calendar-alt mr-1.5"></i> {{ Carbon\Carbon::now()->format('d/m/Y') }}
            </span>
        </div>

        <!-- ===== THÔNG BÁO VỊ TRÍ ===== -->
        @if (!$isValidLocation)
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-4">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 mt-0.5"></i>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-yellow-800 dark:text-yellow-300">
                            ⚠️ Bạn đang ở ngoài phạm vi cho phép
                        </p>
                        <p class="text-xs text-yellow-700 dark:text-yellow-400/70 mt-1">
                            Vui lòng kết nối WiFi công ty để chấm công.
                            IP hiện tại: <strong>{{ $currentIP }}</strong>
                            @if ($currentWiFi)
                                , WiFi: <strong>{{ $currentWiFi }}</strong>
                            @endif
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- ===== TRẠNG THÁI CHẤM CÔNG ===== -->
            <div class="lg:col-span-2">
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="font-semibold text-gray-900 dark:text-white">
                            <i class="fas fa-fingerprint mr-2 text-blue-600 dark:text-blue-400"></i>
                            Trạng thái chấm công
                        </h3>
                        <span
                            class="text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2.5 py-1 rounded-full">
                            Hôm nay
                        </span>
                    </div>
                    <div class="p-6">
                        <div class="text-center py-6">
                            @if (!$chamCongHomNay || !$chamCongHomNay->gio_vao)
                                <!-- Chưa check-in -->
                                <div
                                    class="w-24 h-24 rounded-full bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-clock text-4xl text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <h4 class="text-xl font-semibold text-gray-900 dark:text-white">Chưa check-in</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Hôm nay:
                                    {{ Carbon\Carbon::now()->format('d/m/Y') }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">Giờ làm việc: 08:30 - 17:30</p>

                                @if ($isValidLocation)
                                    <button onclick="handleCheckIn()" id="btnCheckIn"
                                        class="mt-4 px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all duration-300 shadow-lg shadow-blue-600/30 hover:shadow-blue-600/50 flex items-center justify-center mx-auto">
                                        <i class="fas fa-sign-in-alt mr-2"></i>
                                        Check-in ngay
                                    </button>
                                @else
                                    <button disabled
                                        class="mt-4 px-8 py-3 bg-gray-400 cursor-not-allowed text-white font-medium rounded-xl flex items-center justify-center mx-auto">
                                        <i class="fas fa-lock mr-2"></i>
                                        Không thể check-in
                                    </button>
                                    <p class="text-xs text-red-500 dark:text-red-400 mt-2">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        Vui lòng kết nối WiFi công ty
                                    </p>
                                @endif
                            @elseif($chamCongHomNay->gio_vao && !$chamCongHomNay->gio_ra)
                                <!-- Đã check-in -->
                                <div
                                    class="w-24 h-24 rounded-full bg-yellow-50 dark:bg-yellow-900/20 flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-pause-circle text-4xl text-yellow-600 dark:text-yellow-400"></i>
                                </div>
                                <h4 class="text-xl font-semibold text-gray-900 dark:text-white">Đã check-in</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    Check-in lúc: <strong
                                        class="text-gray-900 dark:text-white">{{ $chamCongHomNay->gio_vao_format }}</strong>
                                </p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                    Phương thức: <span
                                        class="font-medium">{{ $chamCongHomNay->phuong_thuc_cham_cong_text }}</span>
                                </p>
                                @if ($chamCongHomNay->phut_di_muon > 0)
                                    <p class="text-sm text-yellow-600 dark:text-yellow-400 mt-1">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Bạn đã đi muộn {{ $chamCongHomNay->phut_di_muon }} phút
                                    </p>
                                @endif

                                @if ($isValidLocation)
                                    <button onclick="handleCheckOut()" id="btnCheckOut"
                                        class="mt-4 px-8 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl transition-all duration-300 shadow-lg shadow-red-600/30 hover:shadow-red-600/50 flex items-center justify-center mx-auto">
                                        <i class="fas fa-sign-out-alt mr-2"></i>
                                        Check-out
                                    </button>
                                @else
                                    <button disabled
                                        class="mt-4 px-8 py-3 bg-gray-400 cursor-not-allowed text-white font-medium rounded-xl flex items-center justify-center mx-auto">
                                        <i class="fas fa-lock mr-2"></i>
                                        Không thể check-out
                                    </button>
                                    <p class="text-xs text-red-500 dark:text-red-400 mt-2">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        Vui lòng ở trong công ty để check-out
                                    </p>
                                @endif
                            @else
                                <!-- Đã hoàn thành -->
                                <div
                                    class="w-24 h-24 rounded-full bg-green-50 dark:bg-green-900/20 flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-check-circle text-4xl text-green-600 dark:text-green-400"></i>
                                </div>
                                <h4 class="text-xl font-semibold text-gray-900 dark:text-white">Đã hoàn thành</h4>
                                <div class="grid grid-cols-2 gap-4 max-w-md mx-auto mt-3">
                                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3">
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Check-in</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">
                                            {{ $chamCongHomNay->gio_vao_format }}</p>
                                    </div>
                                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3">
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Check-out</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">
                                            {{ $chamCongHomNay->gio_ra_format }}</p>
                                    </div>
                                </div>
                                <div class="mt-3 flex items-center justify-center space-x-6">
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Số giờ làm</p>
                                        <p class="text-xl font-bold text-blue-600 dark:text-blue-400">
                                            {{ $chamCongHomNay->so_gio_lam }} giờ</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Tăng ca</p>
                                        <p class="text-xl font-bold text-green-600 dark:text-green-400">
                                            {{ $chamCongHomNay->gio_tang_ca }} giờ</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== THÔNG TIN VỊ TRÍ ===== -->
            <div class="lg:col-span-1">
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-900 dark:text-white">
                            <i class="fas fa-map-marker-alt mr-2 text-blue-600 dark:text-blue-400"></i>
                            Vị trí chấm công
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">

                        {{-- IP hiện tại --}}
                        <div
                            class="flex items-center justify-between p-3 {{ $ipStatus == 'valid' ? 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800' : ($ipStatus == 'invalid' ? 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800' : 'bg-gray-50 dark:bg-gray-700/50') }} rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-network-wired text-blue-600 dark:text-blue-400 w-5"></i>
                                <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">IP</span>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $currentIP }}</span>
                                @if ($ipStatus == 'valid')
                                    <span class="block text-xs text-green-600 dark:text-green-400">✓
                                        {{ $ipMessage }}</span>
                                @elseif($ipStatus == 'invalid')
                                    <span class="block text-xs text-red-500 dark:text-red-400">✗ {{ $ipMessage }}</span>
                                @else
                                    <span class="block text-xs text-gray-400">{{ $ipMessage }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- WiFi hiện tại --}}
                        <div
                            class="flex items-center justify-between p-3 {{ $wifiStatus == 'valid' ? 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800' : ($wifiStatus == 'invalid' ? 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800' : 'bg-gray-50 dark:bg-gray-700/50') }} rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-wifi text-blue-600 dark:text-blue-400 w-5"></i>
                                <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">WiFi</span>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $currentWiFi ?: 'Chưa kết nối' }}
                                </span>
                                @if ($wifiStatus == 'valid')
                                    <span class="block text-xs text-green-600 dark:text-green-400">✓
                                        {{ $wifiMessage }}</span>
                                @elseif($wifiStatus == 'invalid')
                                    <span class="block text-xs text-red-500 dark:text-red-400">✗ {{ $wifiMessage }}</span>
                                @else
                                    <span class="block text-xs text-gray-400">{{ $wifiMessage }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- Thiết bị --}}
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-laptop text-purple-600 dark:text-purple-400 w-5"></i>
                                <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">Thiết bị</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900 dark:text-white truncate max-w-[140px]">
                                {{ $chamCongHomNay->ten_thiet_bi ?? 'Chưa xác định' }}
                            </span>
                        </div>

                        {{-- Phương thức --}}
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-600 dark:text-green-400 w-5"></i>
                                <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">Phương thức</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $phuongThucText }}
                            </span>
                        </div>

                        {{-- Danh sách được phép --}}
                        <details class="mt-4">
                            <summary
                                class="text-sm font-medium text-blue-600 dark:text-blue-400 cursor-pointer hover:underline">
                                <i class="fas fa-info-circle mr-1"></i> Xem danh sách được phép
                            </summary>
                            <div class="mt-2 space-y-1">
                                @if (count($dsIP) > 0)
                                    <p class="text-xs text-gray-500 dark:text-gray-400">📡 IP được phép:</p>
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        @foreach ($dsIP as $ip)
                                            <span
                                                class="inline-flex items-center gap-1 text-xs {{ $currentIP == $ip ? 'bg-blue-500 text-white' : 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300' }} px-2 py-0.5 rounded-full">
                                                @if ($currentIP == $ip)
                                                    <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span>
                                                @endif
                                                {{ $ip }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                @if (count($dsWiFi) > 0)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">📶 WiFi được phép:</p>
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        @foreach ($dsWiFi as $wifi)
                                            <span
                                                class="inline-flex items-center gap-1 text-xs {{ $currentWiFi && $currentWiFi == $wifi ? 'bg-green-500 text-white' : 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300' }} px-2 py-0.5 rounded-full">
                                                @if ($currentWiFi && $currentWiFi == $wifi)
                                                    <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span>
                                                @endif
                                                {{ $wifi }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                @if (count($dsIP) == 0 && count($dsWiFi) == 0)
                                    <p class="text-xs text-gray-400">Chưa có cấu hình nào</p>
                                @endif
                            </div>
                        </details>

                    </div>
                </div>
            </div>
        </div>

        <!-- ===== LỊCH SỬ 7 NGÀY ===== -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900 dark:text-white">
                    <i class="fas fa-history mr-2 text-blue-600 dark:text-blue-400"></i>
                    Lịch sử chấm công 7 ngày gần nhất
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
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Ngày</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Check-in</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Check-out</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Số giờ</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($lichSu as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    {{ Carbon\Carbon::parse($item->ngay_cham_cong)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    {{ $item->gio_vao_format ?? '--:--' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    {{ $item->gio_ra_format ?? '--:--' }}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $item->so_gio_lam ?? 0 }} giờ
                                </td>
                                <td class="px-6 py-4">
                                    @switch($item->trang_thai)
                                        @case('den_som')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                                <i class="fas fa-arrow-up mr-1"></i> Đến sớm
                                            </span>
                                        @break

                                        @case('dung_gio')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                                <i class="fas fa-check-circle mr-1"></i> Đúng giờ
                                            </span>
                                        @break

                                        @case('di_muon')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300">
                                                <i class="fas fa-exclamation-triangle mr-1"></i> Đi muộn
                                                ({{ $item->phut_di_muon }}p)
                                            </span>
                                        @break

                                        @case('ve_som')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300">
                                                <i class="fas fa-exclamation-triangle mr-1"></i> Về sớm
                                                ({{ $item->phut_ve_som }}p)
                                            </span>
                                        @break

                                        @case('tang_ca')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300">
                                                <i class="fas fa-clock mr-1"></i> Tăng ca
                                            </span>
                                        @break

                                        @default
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                                                <i class="fas fa-minus-circle mr-1"></i> Chưa chấm công
                                            </span>
                                    @endswitch
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
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

        <!-- ===== FORM ẨN ===== -->
        <form id="checkInForm" action="{{ route('employee.cham-cong.check-in') }}" method="POST" style="display:none;">
            @csrf
        </form>

        <form id="checkOutForm" action="{{ route('employee.cham-cong.check-out') }}" method="POST" style="display:none;">
            @csrf
        </form>
    @endsection


    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            // =============================================
            // LẤY THỜI GIAN HIỆN TẠI ĐỊNH DẠNG ISO
            // =============================================
            function getCurrentTimeISO() {
                const now = new Date();
                return now.toISOString();
            }

            // =============================================
            // HÀM CHECK-IN
            // =============================================
            function handleCheckIn() {
                const btn = document.getElementById('btnCheckIn');
                if (!btn) return;

                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Đang xử lý...';

                const token = document.querySelector('meta[name="csrf-token"]')?.content;
                if (!token) {
                    showNotification('error', 'Không tìm thấy CSRF token');
                    resetButton(btn, 'Check-in ngay');
                    return;
                }

                const form = document.getElementById('checkInForm');
                const formData = new FormData(form);

                // ===== GỬI THỜI GIAN THỰC TỪ MÁY TÍNH =====
                const clientTime = getCurrentTimeISO();
                formData.append('client_time', clientTime);

                fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-WiFi-SSID': getWiFiSSID(),
                            'X-MAC-Address': getMACAddress()
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => {
                                throw new Error(`Server trả về lỗi ${response.status}: ${text.substring(0, 200)}`);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            showNotification('success', data.message);
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            showNotification('error', data.message || 'Có lỗi xảy ra');
                            resetButton(btn, 'Check-in ngay');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('error', 'Lỗi: ' + error.message);
                        resetButton(btn, 'Check-in ngay');
                    });
            }

            // =============================================
            // HÀM CHECK-OUT
            // =============================================
            function handleCheckOut() {
                const btn = document.getElementById('btnCheckOut');
                if (!btn) return;

                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Đang xử lý...';

                const token = document.querySelector('meta[name="csrf-token"]')?.content;
                if (!token) {
                    showNotification('error', 'Không tìm thấy CSRF token');
                    resetButton(btn, 'Check-out');
                    return;
                }

                const form = document.getElementById('checkOutForm');
                const formData = new FormData(form);

                // ===== GỬI THỜI GIAN THỰC TỪ MÁY TÍNH =====
                const clientTime = getCurrentTimeISO();
                formData.append('client_time', clientTime);

                fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-WiFi-SSID': getWiFiSSID(),
                            'X-MAC-Address': getMACAddress()
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => {
                                throw new Error(`Server trả về lỗi ${response.status}: ${text.substring(0, 200)}`);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            showNotification('success', data.message);
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            showNotification('error', data.message || 'Có lỗi xảy ra');
                            resetButton(btn, 'Check-out');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('error', 'Lỗi: ' + error.message);
                        resetButton(btn, 'Check-out');
                    });
            }

            // =============================================
            // HÀM PHỤ TRỢ
            // =============================================
            function resetButton(btn, text) {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-sign-in-alt mr-2"></i> ' + text;
                }
            }

            function getWiFiSSID() {
                const saved = localStorage.getItem('wifi_ssid');
                if (saved) return saved;
                return 'HRFlow_WiFi';
            }

            function getMACAddress() {
                const saved = localStorage.getItem('mac_address');
                if (saved) return saved;
                return 'AA:BB:CC:DD:EE:01';
            }

            // =============================================
            // HIỂN THỊ THÔNG BÁO
            // =============================================
            function showNotification(type, message) {
                const colors = {
                    success: 'bg-green-50 dark:bg-green-900/30 border-green-200 dark:border-green-800 text-green-800 dark:text-green-300',
                    error: 'bg-red-50 dark:bg-red-900/30 border-red-200 dark:border-red-800 text-red-800 dark:text-red-300',
                    warning: 'bg-yellow-50 dark:bg-yellow-900/30 border-yellow-200 dark:border-yellow-800 text-yellow-800 dark:text-yellow-300',
                    info: 'bg-blue-50 dark:bg-blue-900/30 border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-300'
                };

                const icons = {
                    success: 'fa-check-circle',
                    error: 'fa-exclamation-circle',
                    warning: 'fa-exclamation-triangle',
                    info: 'fa-info-circle'
                };

                const notification = document.createElement('div');
                notification.className =
                    `fixed top-4 right-4 z-50 max-w-md w-full ${colors[type]} border rounded-xl p-4 shadow-lg transform transition-all duration-300 translate-x-full opacity-0`;
                notification.innerHTML = `
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas ${icons[type]} text-lg"></i>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium">${message}</p>
            </div>
            <button type="button" class="flex-shrink-0 ml-4 text-gray-400 hover:text-gray-600" onclick="this.parentElement.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;

                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.classList.remove('translate-x-full', 'opacity-0');
                }, 100);

                setTimeout(() => {
                    notification.classList.add('translate-x-full', 'opacity-0');
                    setTimeout(() => notification.remove(), 300);
                }, 4000);
            }

            // =============================================
            // HIỂN THỊ THỜI GIAN HIỆN TẠI (DEBUG)
            // =============================================
            console.log('Thời gian hiện tại (client):', new Date().toLocaleString());
            console.log('WiFi SSID đang dùng:', getWiFiSSID());
            console.log('MAC Address đang dùng:', getMACAddress());
        </script>
    @endpush
