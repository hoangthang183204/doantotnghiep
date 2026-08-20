@extends('layouts.admin')

@section('title', 'Lịch sử chấm công - ' . ($nhanVien->hoSo->ho ?? '') . ' ' . ($nhanVien->hoSo->ten ?? ''))

@section('content')
<div class="space-y-6">
    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                    <i class="fas fa-user-clock text-blue-500 mr-3"></i>
                    Lịch sử chấm công
                </h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">
                    Xem lịch sử chấm công chi tiết của nhân viên
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.cham-cong.index') }}" 
                   class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Quay lại
                </a>
            </div>
        </div>
    </div>

    {{-- THÔNG TIN NHÂN VIÊN --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6">
            <div class="flex items-center gap-6">
                @php
                    $hoSo = $nhanVien->hoSo;
                    $avatar = $hoSo && $hoSo->anh_dai_dien && file_exists(public_path('storage/' . $hoSo->anh_dai_dien)) 
                        ? asset('storage/' . $hoSo->anh_dai_dien) 
                        : null;
                    $hoTen = $hoSo ? trim(($hoSo->ho ?? '') . ' ' . ($hoSo->ten ?? '')) : $nhanVien->ten_dang_nhap;
                @endphp
                
                @if($avatar)
                    <img src="{{ $avatar }}" alt="{{ $hoTen }}" 
                         class="w-20 h-20 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600">
                @else
                    <div class="w-20 h-20 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                        {{ strtoupper(substr($hoTen, 0, 1)) }}
                    </div>
                @endif
                
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $hoTen }}</h2>
                    <div class="flex flex-wrap gap-3 mt-1 text-sm text-gray-500 dark:text-gray-400">
                        <span><i class="fas fa-id-card mr-1"></i> Mã NV: {{ $hoSo->ma_nhan_vien ?? 'N/A' }}</span>
                        <span><i class="fas fa-building mr-1"></i> {{ $nhanVien->phongBan->ten_phong_ban ?? 'N/A' }}</span>
                        <span><i class="fas fa-briefcase mr-1"></i> {{ $nhanVien->chucVu->ten ?? 'N/A' }}</span>
                        <span><i class="fas fa-envelope mr-1"></i> {{ $nhanVien->email ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- THỐNG KÊ --}}
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-3">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700 text-center">
            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Tổng ngày</p>
            <p class="text-2xl font-bold text-blue-600">{{ $thongKe['tong_ngay'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700 text-center">
            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Tổng giờ</p>
            <p class="text-2xl font-bold text-green-600">{{ number_format($thongKe['tong_gio'], 1) }}h</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700 text-center">
            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Tổng công</p>
            <p class="text-2xl font-bold text-purple-600">{{ number_format($thongKe['tong_cong'], 2) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700 text-center">
            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Tăng ca</p>
            <p class="text-2xl font-bold text-indigo-600">{{ number_format($thongKe['tong_tang_ca'], 1) }}h</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700 text-center">
            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Đúng giờ</p>
            <p class="text-2xl font-bold text-green-600">{{ $thongKe['dung_gio'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700 text-center">
            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Đi muộn</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $thongKe['di_muon'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700 text-center">
            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Về sớm</p>
            <p class="text-2xl font-bold text-orange-600">{{ $thongKe['ve_som'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700 text-center">
            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Vắng mặt</p>
            <p class="text-2xl font-bold text-red-600">{{ $thongKe['vang_mat'] }}</p>
        </div>
    </div>

    {{-- BỘ LỌC --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-200 dark:border-gray-700">
        <form method="GET" action="{{ route('admin.cham-cong.nhan-vien.show', $nhanVien->id) }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    <i class="fas fa-calendar-alt mr-1"></i> Từ ngày
                </label>
                <input type="date" name="tu_ngay" value="{{ request('tu_ngay') }}"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    <i class="fas fa-calendar-alt mr-1"></i> Đến ngày
                </label>
                <input type="date" name="den_ngay" value="{{ request('den_ngay') }}"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    <i class="fas fa-filter mr-1"></i> Trạng thái
                </label>
                <select name="trang_thai" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="">-- Tất cả --</option>
                    <option value="dung_gio" {{ request('trang_thai') == 'dung_gio' ? 'selected' : '' }}>✅ Đúng giờ</option>
                    <option value="di_muon" {{ request('trang_thai') == 'di_muon' ? 'selected' : '' }}>⚠️ Đi muộn</option>
                    <option value="ve_som" {{ request('trang_thai') == 've_som' ? 'selected' : '' }}>🔻 Về sớm</option>
                    <option value="den_som" {{ request('trang_thai') == 'den_som' ? 'selected' : '' }}>📈 Đến sớm</option>
                    <option value="vang_mat" {{ request('trang_thai') == 'vang_mat' ? 'selected' : '' }}>❌ Vắng mặt</option>
                    <option value="nghi_phep" {{ request('trang_thai') == 'nghi_phep' ? 'selected' : '' }}>📅 Nghỉ phép</option>
                    <option value="khong_cham_cong" {{ request('trang_thai') == 'khong_cham_cong' ? 'selected' : '' }}>⏸️ Không chấm công</option>
                    <option value="tang_ca" {{ request('trang_thai') == 'tang_ca' ? 'selected' : '' }}>🔄 Tăng ca</option>
                </select>
            </div>
            <div class="flex items-end gap-3">
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-search"></i> Tìm kiếm
                </button>
                <a href="{{ route('admin.cham-cong.nhan-vien.show', $nhanVien->id) }}" 
                   class="px-5 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-redo"></i> Làm mới
                </a>
            </div>
        </form>
    </div>

    {{-- BẢNG CHẤM CÔNG --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">#</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Ngày</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Thứ</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Giờ vào</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Giờ ra</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Số giờ</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Số công</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tăng ca</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Trạng thái</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($chamCongs as $index => $cc)
                    @php
                        $statusMap = [
                            'dung_gio' => ['bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300', '✅ Đúng giờ'],
                            'di_muon' => ['bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300', '⚠️ Đi muộn'],
                            've_som' => ['bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300', '🔻 Về sớm'],
                            'den_som' => ['bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300', '📈 Đến sớm'],
                            'vang_mat' => ['bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300', '❌ Vắng mặt'],
                            'khong_cham_cong' => ['bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400', '⏸️ Không chấm công'],
                            'nghi_phep' => ['bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300', '📅 Nghỉ phép'],
                            'tang_ca' => ['bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300', '🔄 Tăng ca'],
                        ];
                        $stt = $statusMap[$cc->trang_thai] ?? ['bg-gray-100 text-gray-700', $cc->trang_thai];
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400 text-center">
                            {{ ($chamCongs->currentPage() - 1) * $chamCongs->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-300">
                            {{ \Carbon\Carbon::parse($cc->ngay_cham_cong)->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400">
                            {{ \Carbon\Carbon::parse($cc->ngay_cham_cong)->locale('vi')->dayName }}
                        </td>
                        <td class="px-4 py-4 text-sm font-mono">
                            {{ $cc->gio_vao ? \Carbon\Carbon::parse($cc->gio_vao)->format('H:i') : '--:--' }}
                            @if(($cc->phut_di_muon ?? 0) > 0)
                                <span class="text-xs text-yellow-600">(+{{ $cc->phut_di_muon }}p)</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-sm font-mono">
                            {{ $cc->gio_ra ? \Carbon\Carbon::parse($cc->gio_ra)->format('H:i') : '--:--' }}
                            @if(($cc->phut_ve_som ?? 0) > 0)
                                <span class="text-xs text-orange-600">(-{{ $cc->phut_ve_som }}p)</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-sm font-semibold text-blue-600">
                            {{ number_format($cc->so_gio_lam ?? 0, 1) }}h
                        </td>
                        <td class="px-4 py-4 text-sm font-semibold text-purple-600">
                            {{ number_format($cc->so_cong ?? 0, 2) }}
                        </td>
                        <td class="px-4 py-4 text-sm font-semibold text-indigo-600">
                            {{ number_format($cc->gio_tang_ca ?? 0, 1) }}h
                        </td>
                        <td class="px-4 py-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $stt[0] }}">
                                {{ $stt[1] }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <a href="{{ route('admin.cham-cong.show', $cc->id) }}" 
                               class="px-3 py-1.5 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900/30 dark:hover:bg-blue-800/50 text-blue-600 dark:text-blue-400 rounded-lg transition text-sm inline-flex items-center gap-1">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-12 text-gray-500 dark:text-gray-400">
                            <i class="fas fa-inbox text-4xl block mb-3 text-gray-300 dark:text-gray-600"></i>
                            <p class="font-medium">Không có dữ liệu chấm công</p>
                            <p class="text-sm">Nhân viên này chưa có bản ghi chấm công nào</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($chamCongs->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex flex-wrap items-center justify-between gap-3">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Hiển thị <strong>{{ $chamCongs->firstItem() ?? 0 }}</strong> - 
                    <strong>{{ $chamCongs->lastItem() ?? 0 }}</strong> 
                    trong tổng <strong>{{ $chamCongs->total() }}</strong> bản ghi
                </div>
                {{ $chamCongs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Custom styles if needed */
</style>
@endpush