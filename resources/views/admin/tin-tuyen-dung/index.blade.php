<!-- resources/views/admin/tin-tuyen-dung/index.blade.php -->
@extends('layouts.admin')

@section('content')
    <div class="p-6 max-w-7xl mx-auto space-y-6">
        <!-- HEADER -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Quản lý tin tuyển dụng
                </h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">
                    Quản lý các tin tuyển dụng và theo dõi số lượng ứng viên
                </p>
            </div>
            <a href="{{ route('admin.tin-tuyen-dung.create') }}"
                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition shadow-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Thêm mới
            </a>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div
                class="p-4 rounded-xl bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 border border-green-100 dark:border-green-800 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div
                class="p-4 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 border border-red-100 dark:border-red-800 text-sm">
                {{ session('error') }}
            </div>
        @endif

        @if (session('warning'))
            <div
                class="p-4 rounded-xl bg-yellow-50 dark:bg-yellow-900/20 text-yellow-700 dark:text-yellow-400 border border-yellow-100 dark:border-yellow-800 text-sm">
                {{ session('warning') }}
            </div>
        @endif

        <!-- THỐNG KÊ TỔNG QUAN + BIỂU ĐỒ -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Thống kê số -->
            <div class="md:col-span-2">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2.5 rounded-xl bg-blue-100 dark:bg-blue-900/30">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-xl font-bold text-blue-600 dark:text-blue-400">{{ $tongQuan['tong_tin'] ?? 0 }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Tổng tin</div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2.5 rounded-xl bg-green-100 dark:bg-green-900/30">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-xl font-bold text-green-600 dark:text-green-400">{{ $tongQuan['dang_tuyen'] ?? 0 }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Đang tuyển</div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2.5 rounded-xl bg-purple-100 dark:bg-purple-900/30">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-xl font-bold text-purple-600 dark:text-purple-400">{{ $tongQuan['tong_ung_vien'] ?? 0 }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Tổng ứng viên</div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2.5 rounded-xl bg-yellow-100 dark:bg-yellow-900/30">
                                <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-xl font-bold text-yellow-600 dark:text-yellow-400">{{ $tongQuan['tam_dung'] ?? 0 }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Tạm dừng</div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2.5 rounded-xl bg-red-100 dark:bg-red-900/30">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-xl font-bold text-red-600 dark:text-red-400">{{ $tongQuan['ket_thuc'] ?? 0 }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Kết thúc</div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2.5 rounded-xl bg-gray-100 dark:bg-gray-700">
                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-xl font-bold text-gray-600 dark:text-gray-400">{{ $tongQuan['nhap'] ?? 0 }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Nháp</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Biểu đồ tròn -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-4">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 text-center">📊 Tổng ứng viên theo trạng thái</h3>
                <div class="relative" style="height: 180px;">
                    <canvas id="candidateChart"></canvas>
                </div>
                <div class="flex flex-wrap justify-center gap-2 mt-3 text-xs">
                    <div class="flex items-center gap-1">
                        <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                        <span class="text-gray-600 dark:text-gray-400">Mới nộp</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
                        <span class="text-gray-600 dark:text-gray-400">Chờ duyệt</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="w-3 h-3 rounded-full bg-cyan-500"></span>
                        <span class="text-gray-600 dark:text-gray-400">Đã duyệt</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="w-3 h-3 rounded-full bg-green-500"></span>
                        <span class="text-gray-600 dark:text-gray-400">Trúng tuyển</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="w-3 h-3 rounded-full bg-red-500"></span>
                        <span class="text-gray-600 dark:text-gray-400">Không đạt</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- THỐNG KÊ ỨNG VIÊN THEO TRẠNG THÁI -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-3 text-center">
                <div class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $tongQuan['ung_vien_moi_nop'] ?? 0 }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">📋 Mới nộp</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-3 text-center">
                <div class="text-lg font-bold text-yellow-600 dark:text-yellow-400">{{ $tongQuan['ung_vien_cho_duyet'] ?? 0 }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">⏳ Chờ duyệt</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-3 text-center">
                <div class="text-lg font-bold text-cyan-600 dark:text-cyan-400">{{ $tongQuan['ung_vien_da_duyet'] ?? 0 }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">✅ Đã duyệt</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-3 text-center">
                <div class="text-lg font-bold text-green-600 dark:text-green-400">{{ $tongQuan['ung_vien_dat'] ?? 0 }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">🏆 Trúng tuyển</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-3 text-center">
                <div class="text-lg font-bold text-red-600 dark:text-red-400">{{ $tongQuan['ung_vien_khong_dat'] ?? 0 }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">❌ Không đạt</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-4">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <input type="text" name="keyword" value="{{ request('keyword') }}"
                        placeholder="Tìm kiếm tin tuyển dụng..."
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/20 outline-none text-sm">
                </div>
                <div>
                    <select name="status"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/20 outline-none text-sm">
                        <option value="">Tất cả trạng thái</option>
                        <option value="dang_tuyen" {{ request('status') == 'dang_tuyen' ? 'selected' : '' }}>Đang tuyển</option>
                        <option value="tam_dung" {{ request('status') == 'tam_dung' ? 'selected' : '' }}>Tạm dừng</option>
                        <option value="ket_thuc" {{ request('status') == 'ket_thuc' ? 'selected' : '' }}>Kết thúc</option>
                        <option value="nhap" {{ request('status') == 'nhap' ? 'selected' : '' }}>Nháp</option>
                    </select>
                </div>
                <div>
                    <select name="phong_ban_id"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/20 outline-none text-sm">
                        <option value="">Tất cả phòng ban</option>
                        @foreach ($phongBans as $phongBan)
                            <option value="{{ $phongBan->id }}"
                                {{ request('phong_ban_id') == $phongBan->id ? 'selected' : '' }}>
                                {{ $phongBan->ten_phong_ban }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button
                        class="w-full px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold transition text-sm">
                        Tìm kiếm
                    </button>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300 text-xs uppercase">
                            <th class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 text-left">Tiêu đề</th>
                            <th class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 text-left">Phòng ban</th>
                            <th class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 text-center">Số lượng</th>
                            <th class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 text-center">Ứng viên</th>
                            <th class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 text-center">Trạng thái</th>
                            <th class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 text-center">Hạn nộp</th>
                            <th class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tinTuyenDungs as $item)
                            <tr class="hover:bg-blue-50/40 dark:hover:bg-gray-700/40 transition">
                                <td class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                                    <a href="{{ route('admin.tin-tuyen-dung.show', $item->id) }}"
                                        class="font-semibold text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400">
                                        {{ $item->tieu_de }}
                                    </a>
                                </td>
                                <td class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 text-gray-600 dark:text-gray-300">
                                    {{ $item->phongBan?->ten_phong_ban ?? 'Chưa xác định' }}
                                </td>
                                <td class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 text-center font-semibold text-gray-900 dark:text-white">
                                    {{ $item->so_vi_tri }}
                                </td>
                                <td class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 text-center">
                                    <span
                                        class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 text-xs font-semibold">
                                        {{ $item->ungViens->count() }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 text-center">
                                    @switch($item->trang_thai)
                                        @case('nhap')
                                            <span
                                                class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400 text-xs font-semibold">
                                                📝 Nháp
                                            </span>
                                        @break

                                        @case('dang_tuyen')
                                            <span
                                                class="px-3 py-1 rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 text-xs font-semibold">
                                                ✅ Đang tuyển
                                            </span>
                                        @break

                                        @case('tam_dung')
                                            <span
                                                class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 text-xs font-semibold">
                                                ⏸️ Tạm dừng
                                            </span>
                                        @break

                                        @case('ket_thuc')
                                            <span
                                                class="px-3 py-1 rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 text-xs font-semibold">
                                                🔚 Kết thúc
                                            </span>
                                        @break

                                        @default
                                            <span
                                                class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400 text-xs font-semibold">
                                                {{ $item->trang_thai }}
                                            </span>
                                    @endswitch
                                </td>
                                <td class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 text-center text-sm text-gray-600 dark:text-gray-300">
                                    {{ $item->han_nop_ho_so ? $item->han_nop_ho_so->format('d/m/Y') : '---' }}
                                </td>
                                <td class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('admin.tin-tuyen-dung.show', $item->id) }}"
                                            class="p-1.5 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/50 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.tin-tuyen-dung.edit', $item->id) }}"
                                            class="p-1.5 rounded-lg bg-yellow-50 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 hover:bg-yellow-100 dark:hover:bg-yellow-900/50 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.tin-tuyen-dung.destroy', $item->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa tin tuyển dụng này?')"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-1.5 rounded-lg bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/50 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-10 text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                                    Không tìm thấy tin tuyển dụng nào
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                {{ $tinTuyenDungs->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('candidateChart').getContext('2d');
            
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Mới nộp', 'Chờ duyệt', 'Đã duyệt', 'Trúng tuyển', 'Không đạt'],
                    datasets: [{
                        data: [
                            {{ $tongQuan['ung_vien_moi_nop'] ?? 0 }},
                            {{ $tongQuan['ung_vien_cho_duyet'] ?? 0 }},
                            {{ $tongQuan['ung_vien_da_duyet'] ?? 0 }},
                            {{ $tongQuan['ung_vien_dat'] ?? 0 }},
                            {{ $tongQuan['ung_vien_khong_dat'] ?? 0 }}
                        ],
                        backgroundColor: ['#3b82f6', '#eab308', '#06b6d4', '#22c55e', '#ef4444'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    let percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                                    return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
@endsection