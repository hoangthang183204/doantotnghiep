@extends('layouts.admin')

@section('title', 'Phê duyệt tăng ca')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Phê duyệt tăng ca</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Quản lý và xét duyệt đơn đăng ký tăng ca nhân viên</p>
            </div>
        </div>
    </div>

    {{-- THỐNG KÊ --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">
            <p class="text-sm text-gray-500 dark:text-gray-400">Tổng đơn</p>
            <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-2">{{ $tongHoSo }}</h3>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">
            <p class="text-sm text-yellow-600">Chờ duyệt</p>
            <h3 class="text-3xl font-bold text-yellow-500 mt-2">{{ $choDuyet }}</h3>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">
            <p class="text-sm text-green-600">Đã duyệt</p>
            <h3 class="text-3xl font-bold text-green-600 mt-2">{{ $daDuyet }}</h3>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">
            <p class="text-sm text-red-500">Từ chối</p>
            <h3 class="text-3xl font-bold text-red-500 mt-2">{{ $tuChoi }}</h3>
        </div>
    </div>

    {{-- THÔNG BÁO --}}
    @if(session('success'))
        <div id="alert-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-sm flex justify-between items-center">
            <span>{{ session('success') }}</span>
            <button onclick="document.getElementById('alert-success').remove()" class="font-bold">×</button>
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-sm">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- BỘ LỌC --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">
        <form method="GET">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block mb-2 font-medium text-sm">Tìm nhân viên</label>
                    <input type="text" name="keyword" value="{{ request('keyword') }}"
                        placeholder="Tên, tên đăng nhập..."
                        class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label class="block mb-2 font-medium text-sm">Trạng thái</label>
                    <select name="trang_thai" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">-- Tất cả --</option>
                        <option value="cho_duyet" {{ request('trang_thai') == 'cho_duyet' ? 'selected' : '' }}>Chờ duyệt</option>
                        <option value="da_duyet"  {{ request('trang_thai') == 'da_duyet'  ? 'selected' : '' }}>Đã duyệt</option>
                        <option value="tu_choi"   {{ request('trang_thai') == 'tu_choi'   ? 'selected' : '' }}>Từ chối</option>
                        <option value="huy"       {{ request('trang_thai') == 'huy'       ? 'selected' : '' }}>Đã huỷ</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-2 font-medium text-sm">Loại tăng ca</label>
                    <select name="loai_tang_ca" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">-- Tất cả --</option>
                        <option value="ngay_thuong" {{ request('loai_tang_ca') == 'ngay_thuong' ? 'selected' : '' }}>Ngày thường</option>
                        <option value="ngay_nghi"   {{ request('loai_tang_ca') == 'ngay_nghi'   ? 'selected' : '' }}>Ngày nghỉ</option>
                        <option value="le_tet"      {{ request('loai_tang_ca') == 'le_tet'      ? 'selected' : '' }}>Lễ / Tết</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-2 font-medium text-sm">Từ ngày</label>
                    <input type="date" name="tu_ngay" value="{{ request('tu_ngay') }}"
                        class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label class="block mb-2 font-medium text-sm">Đến ngày</label>
                    <input type="date" name="den_ngay" value="{{ request('den_ngay') }}"
                        class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
            </div>
            <div class="mt-4 flex gap-3 flex-wrap">
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    Tìm kiếm
                </button>
                <a href="{{ route('admin.tang-ca.index') }}" class="px-5 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                    Làm mới
                </a>
            </div>
        </form>
    </div>

    {{-- BẢNG DANH SÁCH --}}
    <form id="form-hang-loat" method="POST" action="{{ route('admin.tang-ca.duyet-hang-loat') }}">
        @csrf
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">

            {{-- Toolbar duyệt hàng loạt --}}
            <div class="px-5 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center gap-3">
                <input type="checkbox" id="check-all" class="w-4 h-4 cursor-pointer">
                <label for="check-all" class="text-sm text-gray-600 dark:text-gray-300 cursor-pointer">Chọn tất cả</label>
                <button type="submit"
                    onclick="return confirm('Duyệt tất cả đơn đã chọn?')"
                    class="ml-auto px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition">
                    ✓ Duyệt hàng loạt
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left w-10"></th>
                            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200 text-sm">Nhân viên</th>
                            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200 text-sm">Ngày tăng ca</th>
                            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200 text-sm">Giờ</th>
                            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200 text-sm">Số giờ</th>
                            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200 text-sm">Loại</th>
                            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200 text-sm">Lý do</th>
                            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-200 text-sm">Trạng thái</th>
                            <th class="px-4 py-3 text-center text-gray-700 dark:text-gray-200 text-sm">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dangKyList as $item)
                            @php
                                $hoTen = optional($item->nguoi_dung->hoSo)
                                    ? $item->nguoi_dung->hoSo->ho . ' ' . $item->nguoi_dung->hoSo->ten
                                    : $item->nguoi_dung->ten_dang_nhap;
                            @endphp
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750 transition">
                                {{-- Checkbox --}}
                                <td class="px-4 py-3">
                                    @if($item->trang_thai === 'cho_duyet')
                                        <input type="checkbox" name="ids[]" value="{{ $item->id }}" class="row-check w-4 h-4">
                                    @endif
                                </td>

                                {{-- Nhân viên --}}
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-800 dark:text-white">{{ $hoTen }}</div>
                                    <div class="text-xs text-gray-500">{{ $item->nguoi_dung->ten_dang_nhap }}</div>
                                </td>

                                {{-- Ngày --}}
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                    {{ $item->ngay_tang_ca->format('d/m/Y') }}
                                    <div class="text-xs text-gray-400">
                                        {{ ['CN','T2','T3','T4','T5','T6','T7'][$item->ngay_tang_ca->dayOfWeek] }}
                                    </div>
                                </td>

                                {{-- Giờ --}}
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300 text-sm">
                                    {{ $item->gio_bat_dau }} – {{ $item->gio_ket_thuc }}
                                </td>

                                {{-- Số giờ --}}
                                <td class="px-4 py-3">
                                    <span class="font-semibold text-blue-600">{{ $item->so_gio_tang_ca }}h</span>
                                </td>

                                {{-- Loại --}}
                                <td class="px-4 py-3">
                                    @php
                                        $loaiClass = match($item->loai_tang_ca) {
                                            'ngay_thuong' => 'bg-blue-100 text-blue-700',
                                            'ngay_nghi'   => 'bg-purple-100 text-purple-700',
                                            'le_tet'      => 'bg-red-100 text-red-700',
                                            default       => 'bg-gray-100 text-gray-700',
                                        };
                                        $loaiLabel = \App\Models\DangKyTangCa::$loaiLabels[$item->loai_tang_ca] ?? $item->loai_tang_ca;
                                    @endphp
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $loaiClass }}">
                                        {{ $loaiLabel }}
                                    </span>
                                </td>

                                {{-- Lý do --}}
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300 text-sm max-w-xs truncate">
                                    {{ $item->ly_do_tang_ca }}
                                </td>

                                {{-- Trạng thái --}}
                                <td class="px-4 py-3">
                                    @php
                                        $ttClass = match($item->trang_thai) {
                                            'cho_duyet' => 'bg-yellow-100 text-yellow-700',
                                            'da_duyet'  => 'bg-green-100 text-green-700',
                                            'tu_choi'   => 'bg-red-100 text-red-700',
                                            'huy'       => 'bg-gray-100 text-gray-500',
                                            default     => 'bg-gray-100 text-gray-500',
                                        };
                                        $ttLabel = \App\Models\DangKyTangCa::$trangThaiLabels[$item->trang_thai] ?? $item->trang_thai;
                                    @endphp
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $ttClass }}">
                                        {{ $ttLabel }}
                                    </span>
                                </td>

                                {{-- Thao tác --}}
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2 flex-wrap">
                                        {{-- Xem chi tiết --}}
                                        <a href="{{ route('admin.tang-ca.show', $item->id) }}"
                                            class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg text-xs font-medium transition">
                                            Chi tiết
                                        </a>

                                        @if($item->trang_thai === 'cho_duyet')
                                            {{-- Duyệt --}}
                                            <form method="POST" action="{{ route('admin.tang-ca.duyet', $item->id) }}" class="inline">
                                                @csrf
                                                <button type="submit"
                                                    onclick="return confirm('Xác nhận duyệt đơn này?')"
                                                    class="px-3 py-1.5 bg-green-50 hover:bg-green-100 text-green-600 rounded-lg text-xs font-medium transition">
                                                    ✓ Duyệt
                                                </button>
                                            </form>

                                            {{-- Từ chối --}}
                                            <button type="button"
                                                onclick="moModalTuChoi({{ $item->id }}, '{{ addslashes($hoTen) }}')"
                                                class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-xs font-medium transition">
                                                ✗ Từ chối
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-10 text-center text-gray-400">
                                    Không có đơn tăng ca nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Phân trang --}}
            <div class="px-5 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $dangKyList->links() }}
            </div>
        </div>
    </form>

</div>

{{-- MODAL TỪ CHỐI --}}
<div id="modal-tu-choi" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md">
        <div class="p-6">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-1">Từ chối đơn tăng ca</h3>
            <p id="modal-ten-nv" class="text-sm text-gray-500 mb-4"></p>

            <form id="form-tu-choi" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Lý do từ chối <span class="text-red-500">*</span>
                    </label>
                    <textarea name="ly_do_tu_choi" rows="4"
                        placeholder="Nhập lý do từ chối..."
                        class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-red-400"
                        required></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="dongModal()"
                        class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
                        Huỷ
                    </button>
                    <button type="submit"
                        class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                        Xác nhận từ chối
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Check all
    document.getElementById('check-all').addEventListener('change', function () {
        document.querySelectorAll('.row-check').forEach(cb => cb.checked = this.checked);
    });

    // Modal từ chối
    function moModalTuChoi(id, tenNv) {
        document.getElementById('form-tu-choi').action = '/admin/tang-ca/' + id + '/tu-choi';
        document.getElementById('modal-ten-nv').textContent = 'Nhân viên: ' + tenNv;
        document.getElementById('modal-tu-choi').classList.remove('hidden');
    }

    function dongModal() {
        document.getElementById('modal-tu-choi').classList.add('hidden');
        document.getElementById('form-tu-choi').querySelector('textarea').value = '';
    }

    // Đóng modal khi click ra ngoài
    document.getElementById('modal-tu-choi').addEventListener('click', function (e) {
        if (e.target === this) dongModal();
    });
</script>
@endpush
@endsection
