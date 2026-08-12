@extends('layouts.admin')

@section('title', 'Duyệt tăng ca - Trưởng phòng')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-clock mr-3 text-blue-600"></i>
                    Duyệt tăng ca
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Quản lý đơn tăng ca và kiến nghị của nhân viên trong phòng
                    @if (isset($phongBan) && $phongBan)
                        <span class="text-blue-600 font-medium">- {{ $phongBan->ten_phong_ban }}</span>
                    @endif
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('truong-phong.tang-ca.create') }}"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-plus-circle"></i>
                    Tạo đơn cho nhân viên
                </a>
            </div>
        </div>

        {{-- Thống kê --}}
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
                <p class="text-sm text-gray-500 dark:text-gray-400">Tổng số</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $thongKe['tong'] ?? 0 }}</p>
            </div>
            <div
                class="bg-white dark:bg-gray-800 rounded-xl border border-yellow-200 dark:border-yellow-700/50 p-4 shadow-sm">
                <p class="text-sm text-yellow-600 dark:text-yellow-400">Chờ duyệt</p>
                <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $thongKe['cho_duyet'] ?? 0 }}</p>
            </div>
            <div
                class="bg-white dark:bg-gray-800 rounded-xl border border-green-200 dark:border-green-700/50 p-4 shadow-sm">
                <p class="text-sm text-green-600 dark:text-green-400">Đã duyệt</p>
                <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $thongKe['da_duyet'] ?? 0 }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-red-200 dark:border-red-700/50 p-4 shadow-sm">
                <p class="text-sm text-red-600 dark:text-red-400">Từ chối</p>
                <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $thongKe['tu_choi'] ?? 0 }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
                <p class="text-sm text-gray-500 dark:text-gray-400">Đã hủy</p>
                <p class="text-2xl font-bold text-gray-500 dark:text-gray-400">{{ $thongKe['huy'] ?? 0 }}</p>
            </div>
        </div>

        {{-- Bộ lọc --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
            <form method="GET" class="flex flex-wrap items-center gap-3">
                <div class="flex-1 min-w-[150px]">
                    <input type="text" name="ten_nhan_vien" value="{{ request('ten_nhan_vien') }}"
                        placeholder="Tìm kiếm tên nhân viên..."
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <select name="trang_thai"
                        class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">Tất cả trạng thái</option>
                        <option value="cho_duyet" {{ request('trang_thai') == 'cho_duyet' ? 'selected' : '' }}>Chờ duyệt
                        </option>
                        <option value="da_duyet" {{ request('trang_thai') == 'da_duyet' ? 'selected' : '' }}>Đã duyệt
                        </option>
                        <option value="tu_choi" {{ request('trang_thai') == 'tu_choi' ? 'selected' : '' }}>Từ chối</option>
                        <option value="huy" {{ request('trang_thai') == 'huy' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </div>
                <div>
                    <input type="date" name="tu_ngay" value="{{ request('tu_ngay') }}"
                        class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <input type="date" name="den_ngay" value="{{ request('den_ngay') }}"
                        class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                </div>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition">
                    <i class="fas fa-search mr-1"></i> Lọc
                </button>
                <a href="{{ route('truong-phong.tang-ca.index') }}"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium transition">
                    <i class="fas fa-redo mr-1"></i> Reset
                </a>
            </form>
        </div>

        {{-- Danh sách --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700/50">
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                Nhân viên</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                Ngày</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                Giờ</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                Số giờ</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                Loại</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                Trạng thái</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                        @forelse($donTangCa as $item)
                            @php
                                $hoTen = optional($item->nguoi_dung->hoSo)
                                    ? $item->nguoi_dung->hoSo->ho . ' ' . $item->nguoi_dung->hoSo->ten
                                    : $item->nguoi_dung->ten_dang_nhap ?? 'N/A';
                                $loaiLabels = ['ngay_thuong' => 'Ngày thường', 'ngay_nghi' => 'Ngày nghỉ', 'le_tet' => 'Lễ, Tết'];
                                $badgeClasses = [
                                    'cho_duyet' =>
                                        'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                                    'da_duyet' =>
                                        'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                    'tu_choi' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                    'huy' => 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400',
                                ];
                                $trangThaiLabels = [
                                    'cho_duyet' => 'Chờ duyệt',
                                    'da_duyet' => 'Đã duyệt',
                                    'tu_choi' => 'Từ chối',
                                    'huy' => 'Đã hủy',
                                ];

                                $isKienNghi = is_null($item->ngay_tang_ca);
                                $isDaDongY = $item->trang_thai == 'da_duyet';
                                $isChoDuyet = $item->trang_thai == 'cho_duyet';
                                $daTaoDon = !is_null($item->ngay_tang_ca);
                            @endphp
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">#{{ $item->id }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-7 h-7 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 text-xs font-bold">
                                            {{ strtoupper(substr($hoTen, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800 dark:text-white">{{ $hoTen }}</p>
                                            <p class="text-xs text-gray-400">
                                                {{ optional($item->nguoi_dung->hoSo)->ma_nhan_vien ?? 'N/A' }}</p>
                                            @if ($isKienNghi)
                                                <span class="text-xs text-blue-500 font-medium">📝 Kiến nghị</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    @if ($isKienNghi)
                                        <span class="text-gray-400">---</span>
                                    @else
                                        {{ \Carbon\Carbon::parse($item->ngay_tang_ca)->format('d/m/Y') }}
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if ($isKienNghi)
                                        <span class="text-gray-400">---</span>
                                    @else
                                        {{ $item->gio_bat_dau }} - {{ $item->gio_ket_thuc }}
                                    @endif
                                </td>
                                <td
                                    class="px-4 py-3 text-center font-medium {{ $isKienNghi ? 'text-gray-400' : 'text-blue-600 dark:text-blue-400' }}">
                                    {{ $item->so_gio_tang_ca }}h
                                </td>
                                <td class="px-4 py-3">
                                    @if ($isKienNghi)
                                        <span class="text-gray-400 text-xs">--</span>
                                    @else
                                        <span
                                            class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                            {{ $loaiLabels[$item->loai_tang_ca] ?? $item->loai_tang_ca }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-medium {{ $badgeClasses[$item->trang_thai] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $trangThaiLabels[$item->trang_thai] ?? $item->trang_thai }}
                                    </span>
                                    @if ($item->trang_thai == 'da_duyet' && $item->thuc_hien && $item->thuc_hien->trang_thai == 'quan_ly_xac_nhan')
                                        <br><span
                                            class="mt-1 px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400">✅
                                            Hoàn thành</span>
                                    @endif
                                    @if ($isKienNghi)
                                        <br><span
                                            class="mt-1 px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">📝
                                            Kiến nghị</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                        <a href="{{ route('truong-phong.tang-ca.show', $item->id) }}"
                                            class="inline-flex items-center px-2.5 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 rounded-lg text-xs font-medium transition"
                                            title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if ($isKienNghi && $isChoDuyet)
                                            <button onclick="duyetKienNghi({{ $item->id }})"
                                                class="inline-flex items-center px-2.5 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg text-xs font-medium transition"
                                                title="Duyệt kiến nghị">
                                                <i class="fas fa-check"></i> Duyệt
                                            </button>
                                        @endif

                                        @if ($isKienNghi && $isDaDongY && !$daTaoDon)
                                            <a href="{{ route('truong-phong.tang-ca.create', ['kien_nghi_id' => $item->id]) }}"
                                                class="inline-flex items-center px-2.5 py-1.5 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-xs font-medium transition"
                                                title="Tạo đơn tăng ca">
                                                <i class="fas fa-plus-circle mr-1"></i> Tạo đơn
                                            </a>
                                        @endif

                                        @if (!$isKienNghi && $item->trang_thai == 'cho_duyet')
                                            <button onclick="duyetTangCa({{ $item->id }})"
                                                class="inline-flex items-center px-2.5 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg text-xs font-medium transition"
                                                title="Phê duyệt">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button onclick="tuChoiTangCa({{ $item->id }})"
                                                class="inline-flex items-center px-2.5 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs font-medium transition"
                                                title="Từ chối">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-inbox text-4xl mb-3 text-gray-300 dark:text-gray-600"></i>
                                        <p class="font-medium">Không có đơn tăng ca hay kiến nghị nào</p>
                                        <p class="text-sm">Hiện tại không có đơn nào cần xử lý</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($donTangCa->hasPages())
                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                    {{ $donTangCa->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            function duyetTangCa(id) {
                if (!confirm('Bạn có chắc muốn duyệt đơn tăng ca này?')) return;

                fetch(`/truong-phong/tang-ca/${id}/duyet`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast('✅ ' + data.message, 'success');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showToast('❌ ' + data.message, 'error');
                        }
                    })
                    .catch(() => showToast('❌ Có lỗi xảy ra', 'error'));
            }

            function tuChoiTangCa(id) {
                const lyDo = prompt('Nhập lý do từ chối:');
                if (lyDo === null) return;
                if (lyDo.trim() === '') {
                    showToast('⚠️ Vui lòng nhập lý do từ chối', 'warning');
                    return;
                }

                fetch(`/truong-phong/tang-ca/${id}/tu-choi`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            ly_do_tu_choi: lyDo
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast('✅ ' + data.message, 'success');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showToast('❌ ' + data.message, 'error');
                        }
                    })
                    .catch(() => showToast('❌ Có lỗi xảy ra', 'error'));
            }

            // ⭐ DUYỆT KIẾN NGHỊ - GỌI ĐÚNG ROUTE
            function duyetKienNghi(id) {
                if (!confirm('Bạn có chắc muốn duyệt kiến nghị này?')) return;

                fetch(`/truong-phong/tang-ca/${id}/duyet-kien-nghi`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast('✅ ' + data.message, 'success');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showToast('❌ ' + data.message, 'error');
                        }
                    })
                    .catch(() => showToast('❌ Có lỗi xảy ra', 'error'));
            }

            // ⭐ TỪ CHỐI KIẾN NGHỊ
            function tuChoiKienNghi(id) {
                const lyDo = prompt('Nhập lý do từ chối:');
                if (lyDo === null) return;
                if (lyDo.trim() === '') {
                    showToast('⚠️ Vui lòng nhập lý do từ chối', 'warning');
                    return;
                }

                fetch(`/truong-phong/tang-ca/${id}/tu-choi-kien-nghi`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            ly_do_tu_choi: lyDo
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast('✅ ' + data.message, 'success');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showToast('❌ ' + data.message, 'error');
                        }
                    })
                    .catch(() => showToast('❌ Có lỗi xảy ra', 'error'));
            }

            function showToast(message, type = 'success') {
                const colors = {
                    success: 'bg-green-500',
                    error: 'bg-red-500',
                    warning: 'bg-yellow-500'
                };
                const toast = document.createElement('div');
                toast.className =
                    `fixed top-4 right-4 ${colors[type] || 'bg-blue-500'} text-white px-6 py-3 rounded-xl shadow-lg z-50 transition-all duration-300 text-sm font-medium`;
                toast.innerHTML = message;
                document.body.appendChild(toast);
                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(-20px)';
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }
        </script>
    @endpush
@endsection