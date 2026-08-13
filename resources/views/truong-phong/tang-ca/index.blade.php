{{-- resources/views/truong-phong/tang-ca/index.blade.php --}}
@php use App\Models\XinVeSomTangCa; @endphp
@extends('layouts.admin')

@section('title', 'Quản lý tăng ca')

@section('content')
    <div class="space-y-6">

        {{-- ⭐ HIỂN THỊ THÔNG BÁO --}}
        @if (session('success'))
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                <div class="flex items-center gap-3">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                    <p class="text-green-700 dark:text-green-300">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <div class="flex items-center gap-3">
                    <i class="fas fa-exclamation-circle text-red-600 dark:text-red-400 text-xl"></i>
                    <p class="text-red-700 dark:text-red-300">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        {{-- HEADER --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-clock mr-3 text-blue-600 dark:text-blue-400"></i>
                    Quản lý tăng ca
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Phòng: <span class="font-medium text-blue-600">{{ $phongBan->ten_phong_ban ?? 'N/A' }}</span>
                </p>
            </div>
            <a href="{{ route('truong-phong.tang-ca.create') }}"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center gap-2 shadow-sm hover:shadow-md">
                <i class="fas fa-plus-circle"></i>
                Tạo đơn tăng ca
            </a>
        </div>

        {{-- THỐNG KÊ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 text-center border border-gray-100 dark:border-gray-700">
                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $thongKe['tong'] ?? 0 }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Tổng yêu cầu</p>
            </div>
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 text-center border border-gray-100 dark:border-gray-700">
                <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $thongKe['cho_duyet'] ?? 0 }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">⏳ Chờ duyệt</p>
            </div>
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 text-center border border-gray-100 dark:border-gray-700">
                <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $thongKe['da_duyet'] ?? 0 }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">✅ Đã duyệt</p>
            </div>
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 text-center border border-gray-100 dark:border-gray-700">
                <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                    {{ $donTangCa->filter(function ($item) {
                            return $item->trang_thai == 'da_duyet' &&
                                $item->thuc_hien &&
                                $item->thuc_hien->trang_thai == 'quan_ly_xac_nhan';
                        })->count() }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400">✅ Hoàn thành</p>
            </div>
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 text-center border border-gray-100 dark:border-gray-700">
                <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $thongKe['tu_choi'] ?? 0 }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">❌ Từ chối</p>
            </div>
        </div>

        {{-- BỘ LỌC --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                <form method="GET" action="{{ route('truong-phong.tang-ca.index') }}"
                    class="flex flex-wrap items-center gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" name="ten_nhan_vien" placeholder="🔍 Tìm theo tên nhân viên..."
                            value="{{ request('ten_nhan_vien') }}"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <select name="trang_thai"
                            class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tất cả trạng thái</option>
                            <option value="cho_duyet" {{ request('trang_thai') == 'cho_duyet' ? 'selected' : '' }}>⏳ Chờ
                                duyệt</option>
                            <option value="da_duyet" {{ request('trang_thai') == 'da_duyet' ? 'selected' : '' }}>✅ Đã duyệt
                            </option>
                            <option value="tu_choi" {{ request('trang_thai') == 'tu_choi' ? 'selected' : '' }}>❌ Từ chối
                            </option>
                            <option value="huy" {{ request('trang_thai') == 'huy' ? 'selected' : '' }}>🗑️ Đã hủy
                            </option>
                        </select>
                    </div>
                    <div>
                        <input type="date" name="tu_ngay" value="{{ request('tu_ngay') }}"
                            class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <input type="date" name="den_ngay" value="{{ request('den_ngay') }}"
                            class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                            <i class="fas fa-search"></i> Lọc
                        </button>
                        <a href="{{ route('truong-phong.tang-ca.index') }}"
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- DANH SÁCH --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                <h3 class="font-semibold text-gray-900 dark:text-white">📋 Danh sách</h3>
                <span class="text-sm text-gray-500">Tổng: {{ $donTangCa->total() }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Loại</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Nhân viên</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Ngày</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Giờ</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Số giờ</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Loại</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Trạng thái</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Xin về sớm</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($donTangCa as $don)
                            @php
                                $isKienNghi = is_null($don->ngay_tang_ca);
                                $thucHien = $don->thuc_hien;
                                $daXacNhan = $thucHien && $thucHien->trang_thai === 'quan_ly_xac_nhan';
                                $daNhanVienXacNhan = $thucHien && $thucHien->trang_thai === 'nhan_vien_xac_nhan';
                                $daCheckout = $thucHien && $thucHien->thoi_gian_ket_thuc;

                                $loaiLabels = [
                                    'ngay_thuong' => 'Ngày thường',
                                    'ngay_nghi' => 'Ngày nghỉ',
                                    'le_tet' => 'Lễ, Tết',
                                ];
                                $badgeClasses = [
                                    'cho_duyet' =>
                                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                    'da_duyet' =>
                                        'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                    'tu_choi' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                    'huy' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                ];
                                $trangThaiLabels = [
                                    'cho_duyet' => '⏳ Chờ duyệt',
                                    'da_duyet' => '✅ Đã duyệt',
                                    'tu_choi' => '❌ Từ chối',
                                    'huy' => '🗑️ Đã hủy',
                                ];

                                $canApprove = $don->trang_thai == 'cho_duyet';

                                // ⭐ Lấy đơn xin về sớm
                                $xinVeSom = $don->xin_ve_som;
                                $canDuyetXinVeSom = $xinVeSom && $xinVeSom->trang_thai == 'cho_duyet';
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-4 py-3">
                                    @if ($isKienNghi)
                                        <span
                                            class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                                            📝 Kiến nghị
                                        </span>
                                    @else
                                        <span
                                            class="px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300">
                                            📄 Đơn tăng ca
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                    {{ optional($don->nguoi_dung->hoSo)->ho }} {{ optional($don->nguoi_dung->hoSo)->ten }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                    @if ($isKienNghi)
                                        {{ Carbon\Carbon::parse($don->created_at)->format('d/m/Y') }}
                                    @else
                                        {{ Carbon\Carbon::parse($don->ngay_tang_ca)->format('d/m/Y') }}
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                    @if ($isKienNghi)
                                        <span class="text-gray-400">---</span>
                                    @else
                                        {{ $don->gio_bat_dau }} - {{ $don->gio_ket_thuc }}
                                    @endif
                                </td>
                                <td
                                    class="px-4 py-3 text-sm font-medium {{ $isKienNghi ? 'text-gray-400' : 'text-blue-600 dark:text-blue-400' }}">
                                    {{ number_format($don->so_gio_tang_ca ?? 0, 1, ',', '') }} giờ
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                    {{ $loaiLabels[$don->loai_tang_ca] ?? $don->loai_tang_ca }}
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-medium {{ $badgeClasses[$don->trang_thai] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $trangThaiLabels[$don->trang_thai] ?? $don->trang_thai }}
                                    </span>
                                    @if ($don->trang_thai == 'da_duyet' && !$isKienNghi)
                                        @if ($daXacNhan)
                                            <span
                                                class="ml-1 px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">
                                                ✅ Hoàn thành
                                            </span>
                                        @elseif($daNhanVienXacNhan)
                                            <span
                                                class="ml-1 px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                                ⏳ Chờ xác nhận
                                            </span>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if ($xinVeSom)
                                        <span
                                            class="px-2 py-1 rounded-full text-xs font-medium 
                                        @if ($xinVeSom->trang_thai == 'cho_duyet') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                        @elseif($xinVeSom->trang_thai == 'da_duyet') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                        @elseif($xinVeSom->trang_thai == 'tu_choi') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif
                                    ">
                                            {{ XinVeSomTangCa::$trangThaiLabels[$xinVeSom->trang_thai] ?? $xinVeSom->trang_thai }}
                                            ({{ $xinVeSom->so_phut_ve_som }}p)
                                        </span>
                                        @if ($canDuyetXinVeSom)
                                            <div class="flex gap-1 mt-1">
                                                <form
                                                    action="{{ route('truong-phong.tang-ca.duyet-xin-ve-som', $xinVeSom->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="px-2 py-1 bg-green-500 hover:bg-green-600 text-white text-xs rounded transition"
                                                        onclick="return confirm('Duyệt đơn xin về sớm?')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <button onclick="showTuChoiXinVeSomModal({{ $xinVeSom->id }})"
                                                    class="px-2 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded transition">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-400">---</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2 flex-wrap">
                                        {{-- Nút Xem chi tiết --}}
                                        <a href="{{ route('truong-phong.tang-ca.show', $don->id) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 rounded-lg transition"
                                            title="Xem chi tiết">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>

                                        {{-- ⭐ NÚT TẠO ĐƠN TỪ KIẾN NGHỊ ĐÃ DUYỆT --}}
                                        @if ($isKienNghi && $don->trang_thai == 'da_duyet')
                                            <a href="{{ route('truong-phong.tang-ca.create', ['kien_nghi_id' => $don->id]) }}"
                                                class="inline-flex items-center justify-center px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white rounded-lg transition text-sm font-medium"
                                                title="Tạo đơn tăng ca từ kiến nghị">
                                                <i class="fas fa-plus-circle mr-1"></i> Tạo đơn
                                            </a>
                                        @endif

                                        {{-- ⭐ DUYỆT/TỪ CHỐI TRONG INDEX --}}
                                        @if ($canApprove)
                                            @if ($isKienNghi)
                                                {{-- Duyệt kiến nghị --}}
                                                <form
                                                    action="{{ route('truong-phong.tang-ca.duyet-kien-nghi', $don->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="inline-flex items-center justify-center w-8 h-8 bg-green-50 text-green-600 hover:bg-green-100 dark:bg-green-900/30 dark:text-green-400 dark:hover:bg-green-900/50 rounded-lg transition"
                                                        onclick="return confirm('Duyệt kiến nghị tăng ca này?')"
                                                        title="Duyệt kiến nghị">
                                                        <i class="fas fa-check text-sm"></i>
                                                    </button>
                                                </form>
                                                {{-- Từ chối kiến nghị --}}
                                                <button onclick="showTuChoiModalIndex({{ $don->id }}, true)"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50 rounded-lg transition"
                                                    title="Từ chối kiến nghị">
                                                    <i class="fas fa-times text-sm"></i>
                                                </button>
                                            @else
                                                {{-- Duyệt đơn tăng ca --}}
                                                <form action="{{ route('truong-phong.tang-ca.duyet', $don->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="inline-flex items-center justify-center w-8 h-8 bg-green-50 text-green-600 hover:bg-green-100 dark:bg-green-900/30 dark:text-green-400 dark:hover:bg-green-900/50 rounded-lg transition"
                                                        onclick="return confirm('Duyệt đơn tăng ca này?')"
                                                        title="Duyệt đơn">
                                                        <i class="fas fa-check text-sm"></i>
                                                    </button>
                                                </form>
                                                {{-- Từ chối đơn tăng ca --}}
                                                <button onclick="showTuChoiModalIndex({{ $don->id }}, false)"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50 rounded-lg transition"
                                                    title="Từ chối đơn">
                                                    <i class="fas fa-times text-sm"></i>
                                                </button>
                                            @endif
                                        @endif

                                        {{-- Xác nhận hoàn thành --}}
                                        @if ($don->trang_thai == 'da_duyet' && !$isKienNghi && $daNhanVienXacNhan && !$daXacNhan)
                                            <form action="{{ route('truong-phong.tang-ca.approve-thuc-hien', $don->id) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-purple-50 text-purple-600 hover:bg-purple-100 dark:bg-purple-900/30 dark:text-purple-400 dark:hover:bg-purple-900/50 rounded-lg transition"
                                                    onclick="return confirm('Xác nhận nhân viên đã hoàn thành tăng ca?')"
                                                    title="Xác nhận hoàn thành">
                                                    <i class="fas fa-check-double text-sm"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-inbox text-2xl block mb-2 text-gray-300 dark:text-gray-600"></i>
                                    Chưa có dữ liệu tăng ca
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($donTangCa->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                    {{ $donTangCa->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- MODAL TỪ CHỐI --}}
    <div id="tuChoiModalIndex" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md p-6 mx-4 animate-scale-up">
            <div class="flex items-center gap-3 mb-4">
                <div
                    class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-times-circle text-red-500 text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Từ chối</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Nhập lý do từ chối</p>
                </div>
            </div>

            <form action="" method="POST" id="tuChoiFormIndex">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Lý do từ chối <span class="text-red-500">*</span>
                    </label>
                    <textarea name="ly_do_tu_choi" id="lyDoTuChoiIndex" rows="4"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-red-500 resize-none transition"
                        placeholder="Nhập lý do từ chối (tối thiểu 10 ký tự)..." required></textarea>
                    <div class="flex justify-between mt-2">
                        <span class="text-xs text-gray-400">Tối thiểu 10 ký tự</span>
                        <span id="lyDoTuChoiCountIndex" class="text-xs text-gray-400">0/500</span>
                    </div>
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="closeTuChoiModalIndex()"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
                        Hủy
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-check"></i>
                        Xác nhận từ chối
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ⭐ MODAL TỪ CHỐI XIN VỀ SỚM --}}
    <div id="tuChoiXinVeSomModal"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md p-6 mx-4 animate-scale-up">
            <div class="flex items-center gap-3 mb-4">
                <div
                    class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-times-circle text-red-500 text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Từ chối xin về sớm</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Nhập lý do từ chối</p>
                </div>
            </div>

            <form action="" method="POST" id="tuChoiXinVeSomForm">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Lý do từ chối <span class="text-red-500">*</span>
                    </label>
                    <textarea name="ly_do_tu_choi" id="lyDoTuChoiXinVeSom" rows="4"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-red-500 resize-none transition"
                        placeholder="Nhập lý do từ chối (tối thiểu 10 ký tự)..." required></textarea>
                    <div class="flex justify-between mt-2">
                        <span class="text-xs text-gray-400">Tối thiểu 10 ký tự</span>
                        <span id="lyDoTuChoiXinVeSomCount" class="text-xs text-gray-400">0/500</span>
                    </div>
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="closeTuChoiXinVeSomModal()"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
                        Hủy
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-check"></i>
                        Xác nhận từ chối
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .animate-scale-up {
            animation: scaleUp 0.25s ease-out;
        }

        @keyframes scaleUp {
            from {
                transform: scale(0.9);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>

@endsection

@push('scripts')
    <script>
        // ⭐ LƯU ROUTES VÀO BIẾN JAVASCRIPT
        const ROUTES_INDEX = {
            tuChoiDon: '{{ route('truong-phong.tang-ca.tu-choi', ['id' => ':id']) }}',
            tuChoiKienNghi: '{{ route('truong-phong.tang-ca.tu-choi-kien-nghi', ['id' => ':id']) }}',
            tuChoiXinVeSom: '{{ route('truong-phong.tang-ca.tu-choi-xin-ve-som', ['id' => ':id']) }}',
        };

        let currentIdIndex = null;
        let isKienNghiIndex = false;

        // ⭐ HIỂN THỊ MODAL TỪ CHỐI KIẾN NGHỊ/ĐƠN TĂNG CA
        function showTuChoiModalIndex(id, isKienNghiFlag) {
            currentIdIndex = id;
            isKienNghiIndex = isKienNghiFlag;
            const modal = document.getElementById('tuChoiModalIndex');
            const form = document.getElementById('tuChoiFormIndex');

            let url = isKienNghiFlag ? ROUTES_INDEX.tuChoiKienNghi : ROUTES_INDEX.tuChoiDon;
            url = url.replace(':id', id);
            form.action = url;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.getElementById('lyDoTuChoiIndex').value = '';
            document.getElementById('lyDoTuChoiCountIndex').textContent = '0/500';
        }

        function closeTuChoiModalIndex() {
            document.getElementById('tuChoiModalIndex').classList.add('hidden');
            document.getElementById('tuChoiModalIndex').classList.remove('flex');
            currentIdIndex = null;
        }

        // ⭐ HIỂN THỊ MODAL TỪ CHỐI XIN VỀ SỚM
        function showTuChoiXinVeSomModal(id) {
            const modal = document.getElementById('tuChoiXinVeSomModal');
            const form = document.getElementById('tuChoiXinVeSomForm');

            let url = ROUTES_INDEX.tuChoiXinVeSom.replace(':id', id);
            form.action = url;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.getElementById('lyDoTuChoiXinVeSom').value = '';
            document.getElementById('lyDoTuChoiXinVeSomCount').textContent = '0/500';
        }

        function closeTuChoiXinVeSomModal() {
            document.getElementById('tuChoiXinVeSomModal').classList.add('hidden');
            document.getElementById('tuChoiXinVeSomModal').classList.remove('flex');
        }

        // ⭐ ĐẾM SỐ KÝ TỰ
        document.getElementById('lyDoTuChoiIndex').addEventListener('input', function() {
            const count = this.value.length;
            document.getElementById('lyDoTuChoiCountIndex').textContent = count + '/500';
        });

        document.getElementById('lyDoTuChoiXinVeSom').addEventListener('input', function() {
            const count = this.value.length;
            document.getElementById('lyDoTuChoiXinVeSomCount').textContent = count + '/500';
        });

        // ⭐ XỬ LÝ SUBMIT FORM TỪ CHỐI - KHÔNG DÙNG AJAX
        document.getElementById('tuChoiFormIndex').addEventListener('submit', function(e) {
            const lyDo = document.getElementById('lyDoTuChoiIndex').value.trim();
            if (lyDo.length < 10) {
                e.preventDefault();
                alert('⚠️ Lý do từ chối phải có ít nhất 10 ký tự!');
                return false;
            }
            // Cho phép submit bình thường, trang sẽ reload
            return true;
        });

        // ⭐ XỬ LÝ SUBMIT FORM TỪ CHỐI XIN VỀ SỚM - KHÔNG DÙNG AJAX
        document.getElementById('tuChoiXinVeSomForm').addEventListener('submit', function(e) {
            const lyDo = document.getElementById('lyDoTuChoiXinVeSom').value.trim();
            if (lyDo.length < 10) {
                e.preventDefault();
                alert('⚠️ Lý do từ chối phải có ít nhất 10 ký tự!');
                return false;
            }
            // Cho phép submit bình thường, trang sẽ reload
            return true;
        });

        // ⭐ XỬ LÝ DUYỆT KIẾN NGHỊ - KHÔNG DÙNG AJAX
        document.querySelectorAll('form[action*="duyet-kien-nghi"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm('Duyệt kiến nghị tăng ca này?')) {
                    e.preventDefault();
                    return false;
                }
                return true;
            });
        });

        // ⭐ XỬ LÝ DUYỆT ĐƠN TĂNG CA - KHÔNG DÙNG AJAX
        document.querySelectorAll('form[action*="/duyet"]:not([action*="duyet-kien-nghi"])').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm('Duyệt đơn tăng ca này?')) {
                    e.preventDefault();
                    return false;
                }
                return true;
            });
        });

        // ⭐ XỬ LÝ DUYỆT XIN VỀ SỚM - KHÔNG DÙNG AJAX
        document.querySelectorAll('form[action*="duyet-xin-ve-som"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm('Duyệt đơn xin về sớm?')) {
                    e.preventDefault();
                    return false;
                }
                return true;
            });
        });

        // ⭐ XỬ LÝ XÁC NHẬN HOÀN THÀNH - KHÔNG DÙNG AJAX
        document.querySelectorAll('form[action*="approve-thuc-hien"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm('Xác nhận nhân viên đã hoàn thành tăng ca?')) {
                    e.preventDefault();
                    return false;
                }
                return true;
            });
        });

        // ⭐ CLICK OUTSIDE ĐỂ ĐÓNG MODAL
        document.getElementById('tuChoiModalIndex').addEventListener('click', function(e) {
            if (e.target === this) closeTuChoiModalIndex();
        });

        document.getElementById('tuChoiXinVeSomModal').addEventListener('click', function(e) {
            if (e.target === this) closeTuChoiXinVeSomModal();
        });

        // ⭐ ESC ĐỂ ĐÓNG MODAL
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeTuChoiModalIndex();
                closeTuChoiXinVeSomModal();
            }
        });
    </script>
@endpush