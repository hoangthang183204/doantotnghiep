@extends('layouts.admin')

@section('title', 'Thực hiện tăng ca')

@section('content')

<div class="space-y-6 bg-gray-50 dark:bg-[#0f172a] min-h-screen p-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800/80 backdrop-blur border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    Thực hiện tăng ca
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">
                    Quản lý kết quả thực hiện tăng ca của nhân viên
                </p>
            </div>

        </div>
    </div>

    {{-- THỐNG KÊ --}}
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

        @php
            $cards = [
                ['label' => 'Tổng hồ sơ', 'value' => $tongSo, 'color' => 'text-gray-800 dark:text-gray-100'],
                ['label' => 'Chưa làm', 'value' => $chuaLam, 'color' => 'text-gray-600 dark:text-gray-300'],
                ['label' => 'Đang làm', 'value' => $dangLam, 'color' => 'text-blue-600 dark:text-blue-300'],
                ['label' => 'Hoàn thành', 'value' => $hoanThanh, 'color' => 'text-green-600 dark:text-green-300'],
                ['label' => 'Không hoàn thành', 'value' => $khongHoanThanh, 'color' => 'text-red-600 dark:text-red-300'],
            ];
        @endphp

        @foreach ($cards as $card)
            <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-5">
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $card['label'] }}</p>
                <h3 class="text-3xl font-bold mt-2 {{ $card['color'] }}">
                    {{ $card['value'] }}
                </h3>
            </div>
        @endforeach

    </div>

    {{-- ALERT --}}
    @if (session('success'))
        <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg flex justify-between">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="font-bold">×</button>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- FILTER --}}
    <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-5">

        <form method="GET">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <input type="text" name="keyword" value="{{ request('keyword') }}"
                    placeholder="Tên hoặc tài khoản..."
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500">

                <select name="trang_thai"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">

                    <option value="">-- Tất cả --</option>
                    <option value="chua_lam" @selected(request('trang_thai')=='chua_lam')>Chưa làm</option>
                    <option value="dang_lam" @selected(request('trang_thai')=='dang_lam')>Đang làm</option>
                    <option value="hoan_thanh" @selected(request('trang_thai')=='hoan_thanh')>Hoàn thành</option>
                    <option value="khong_hoan_thanh" @selected(request('trang_thai')=='khong_hoan_thanh')>Không hoàn thành</option>

                </select>

                <input type="date" name="tu_ngay" value="{{ request('tu_ngay') }}"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">

                <input type="date" name="den_ngay" value="{{ request('den_ngay') }}"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">

            </div>

            <div class="mt-4 flex gap-3">

                <button type="submit"
                    class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                    Tìm kiếm
                </button>

                <a href="{{ route('admin.thuc-hien-tang-ca.index') }}"
                    class="px-5 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white rounded-lg">
                    Làm mới
                </a>

            </div>

        </form>

    </div>

    {{-- TABLE --}}
    <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">

        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead class="bg-gray-100 dark:bg-gray-800/60">
                    <tr class="text-left text-sm text-gray-700 dark:text-gray-300">
                        <th class="px-4 py-3">Nhân viên</th>
                        <th class="px-4 py-3">Ngày TC</th>
                        <th class="px-4 py-3">Giờ đăng ký</th>
                        <th class="px-4 py-3">Giờ thực tế</th>
                        <th class="px-4 py-3">Số giờ TT</th>
                        <th class="px-4 py-3">Công TC</th>
                        <th class="px-4 py-3">Trạng thái</th>
                        <th class="px-4 py-3 text-center">Thao tác</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($danhSach as $item)

                        @php
                            $dangKy = $item->dang_ky;

                            $hoTen = optional($dangKy->nguoi_dung->hoSo)
                                ? $dangKy->nguoi_dung->hoSo->ho . ' ' . $dangKy->nguoi_dung->hoSo->ten
                                : $dangKy->nguoi_dung->ten_dang_nhap;

                            $badge = match ($item->trang_thai) {
                                'chua_lam' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200',
                                'dang_lam' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
                                'hoan_thanh' => 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300',
                                'khong_hoan_thanh' => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
                            };
                        @endphp

                        <tr class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700/50">

                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900 dark:text-white">{{ $hoTen }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $dangKy->nguoi_dung->ten_dang_nhap }}
                                </div>
                            </td>

                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                {{ $dangKy->ngay_tang_ca->format('d/m/Y') }}
                            </td>

                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                {{ substr($dangKy->gio_bat_dau,0,5) }} - {{ substr($dangKy->gio_ket_thuc,0,5) }}
                            </td>

                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                {{ $item->gio_bat_dau_thuc_te ? substr($item->gio_bat_dau_thuc_te,0,5) : '--:--' }}
                                -
                                {{ $item->gio_ket_thuc_thuc_te ? substr($item->gio_ket_thuc_thuc_te,0,5) : '--:--' }}
                            </td>

                            <td class="px-4 py-3 font-semibold text-blue-600 dark:text-blue-300">
                                {{ $item->so_gio_tang_ca_thuc_te }}
                            </td>

                            <td class="px-4 py-3 font-semibold text-green-600 dark:text-green-300">
                                {{ $item->so_cong_tang_ca }}
                            </td>

                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $badge }}">
                                    {{ \App\Models\ThucHienTangCa::$trangThaiLabels[$item->trang_thai] }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.thuc-hien-tang-ca.show', $item->id) }}"
                                        class="px-3 py-1.5 bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-100 dark:hover:bg-blue-800 text-blue-600 dark:text-blue-300 rounded-lg text-xs">
                                        Chi tiết
                                    </a>

                                    <a href="{{ route('admin.thuc-hien-tang-ca.edit', $item->id) }}"
                                        class="px-3 py-1.5 bg-yellow-50 dark:bg-yellow-900/30 hover:bg-yellow-100 dark:hover:bg-yellow-800 text-yellow-700 dark:text-yellow-300 rounded-lg text-xs">
                                        Cập nhật
                                    </a>
                                </div>
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-10 text-center text-gray-400 dark:text-gray-500">
                                Chưa có dữ liệu thực hiện tăng ca.
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="px-5 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $danhSach->links() }}
        </div>

    </div>

</div>

@endsection