@extends('layouts.admin')

@section('title', 'Chi tiết hợp đồng')

@section('content')
<div class="space-y-6 bg-gray-50 dark:bg-[#0f172a] min-h-screen p-6">

    <div class="bg-white dark:bg-gray-800/80 backdrop-blur border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">📄 Chi tiết hợp đồng lao động</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Thông tin chi tiết của hợp đồng</p>
            </div>
            <a href="{{ route('admin.hop-dong.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-xl transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Quay lại
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 text-green-700 px-4 py-3 rounded-xl">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 dark:bg-red-900/30 border border-red-400 text-red-700 px-4 py-3 rounded-xl">❌ {{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Thông tin nhân viên --}}
        <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">
            <div class="border-b px-6 py-4 bg-gray-50 dark:bg-gray-700/30">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">👤 Thông tin nhân viên</h3>
            </div>
            <div class="p-6 space-y-3">
                <div class="flex"><div class="w-32 text-gray-500">Họ tên:</div><div>{{ optional(optional($hopDong->nguoiDung)->hoSo)->ho ?? '' }} {{ optional(optional($hopDong->nguoiDung)->hoSo)->ten ?? '' }}</div></div>
                <div class="flex"><div class="w-32 text-gray-500">Mã NV:</div><div>{{ optional(optional($hopDong->nguoiDung)->hoSo)->ma_nhan_vien ?? 'N/A' }}</div></div>
                <div class="flex"><div class="w-32 text-gray-500">Chức vụ:</div><div>{{ $hopDong->chucVu->ten ?? $hopDong->chuc_vu ?? 'N/A' }}</div></div>
                <div class="flex"><div class="w-32 text-gray-500">Phòng ban:</div><div>{{ optional($hopDong->nguoiDung->phongBan)->ten_phong_ban ?? 'N/A' }}</div></div>
            </div>
        </div>

        {{-- Thông tin hợp đồng --}}
        <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">
            <div class="border-b px-6 py-4 bg-gray-50">
                <h3 class="text-lg font-semibold">📋 Thông tin hợp đồng</h3>
            </div>
            <div class="p-6 space-y-3">
                <div class="flex"><div class="w-32 text-gray-500">Số hợp đồng:</div><div class="font-semibold">{{ $hopDong->so_hop_dong }}</div></div>
                <div class="flex"><div class="w-32 text-gray-500">Loại hợp đồng:</div><div><span class="px-2 py-1 rounded-full text-xs bg-blue-100">{{ $hopDong->loai_hop_dong == 'xac_dinh_thoi_han' ? 'Xác định thời hạn' : 'Không xác định' }}</span></div></div>
                <div class="flex"><div class="w-32 text-gray-500">Ngày bắt đầu:</div><div>{{ \Carbon\Carbon::parse($hopDong->ngay_bat_dau)->format('d/m/Y') }}</div></div>
                <div class="flex"><div class="w-32 text-gray-500">Ngày kết thúc:</div><div>{{ $hopDong->ngay_ket_thuc ? \Carbon\Carbon::parse($hopDong->ngay_ket_thuc)->format('d/m/Y') : '---' }}</div></div>
                <div class="flex"><div class="w-32 text-gray-500">Lương cơ bản:</div><div class="font-semibold text-green-600">{{ number_format($hopDong->luong_co_ban, 0, ',', '.') }} đ</div></div>
                <div class="flex"><div class="w-32 text-gray-500">Phụ cấp:</div><div>{{ number_format($hopDong->phu_cap ?? 0, 0, ',', '.') }} đ</div></div>
                <div class="flex"><div class="w-32 text-gray-500">Địa điểm:</div><div>{{ $hopDong->dia_diem_lam_viec }}</div></div>
                <div class="flex"><div class="w-32 text-gray-500">Trạng thái HĐ:</div><div><span class="px-2 py-1 rounded-full text-xs {{ $hopDong->trang_thai_hop_dong == 'hieu_luc' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">{{ ucfirst(str_replace('_', ' ', $hopDong->trang_thai_hop_dong)) }}</span></div></div>
                <div class="flex"><div class="w-32 text-gray-500">Trạng thái ký:</div><div><span class="px-2 py-1 rounded-full text-xs {{ $hopDong->trang_thai_ky == 'da_ky' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">{{ ucfirst(str_replace('_', ' ', $hopDong->trang_thai_ky)) }}</span></div></div>
            </div>
        </div>
    </div>

    @if($hopDong->duong_dan_file)
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-blue-600 mb-3">📎 File hợp đồng gốc</h3>
        @foreach(array_filter(explode(';', $hopDong->duong_dan_file)) as $file)
            <a href="{{ asset('storage/' . trim($file)) }}" target="_blank" class="inline-flex items-center gap-2 p-2 bg-blue-50 rounded-lg mr-2">📄 {{ basename(trim($file)) }}</a>
        @endforeach
    </div>
    @endif

    @if($hopDong->file_hop_dong_da_ky)
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-green-600 mb-3">✍️ File hợp đồng đã ký</h3>
        @foreach(array_filter(explode(';', $hopDong->file_hop_dong_da_ky)) as $file)
            <a href="{{ asset('storage/' . trim($file)) }}" target="_blank" class="inline-flex items-center gap-2 p-2 bg-green-50 rounded-lg mr-2">✅ {{ basename(trim($file)) }}</a>
        @endforeach
        @if($hopDong->thoi_gian_ky)<p class="text-sm text-gray-500 mt-2">Ký lúc: {{ \Carbon\Carbon::parse($hopDong->thoi_gian_ky)->format('d/m/Y H:i') }}</p>@endif
    </div>
    @endif

    @if(in_array(auth()->user()->vaiTros->pluck('name')->first() ?? '', ['admin', 'hr']) && $hopDong->trang_thai_hop_dong == 'tao_moi')
    <div class="bg-yellow-50 rounded-xl p-6 text-center">
        <p class="mb-4">Hợp đồng đang ở trạng thái tạo mới. Hãy gửi cho nhân viên để ký.</p>
        <form action="{{ route('admin.hop-dong.gui-ky', $hopDong->id) }}" method="POST">
            @csrf
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">📨 Gửi cho nhân viên</button>
        </form>
    </div>
    @endif

    @if(in_array(auth()->user()->vaiTros->pluck('name')->first() ?? '', ['admin', 'hr']) && in_array($hopDong->trang_thai_hop_dong, ['hieu_luc', 'chua_hieu_luc', 'het_han']) && $hopDong->trang_thai_ky != 'da_ky')
    <div class="bg-red-50 rounded-xl p-6 text-center">
        <button onclick="showHuyForm()" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">❌ Hủy hợp đồng</button>
        <div id="huyForm" style="display:none;" class="mt-4 p-4 bg-white rounded-lg">
            <form action="{{ route('admin.hop-dong.huy', $hopDong->id) }}" method="POST">
                @csrf
                <textarea name="ly_do_huy" class="w-full p-3 border rounded-lg" rows="3" placeholder="Nhập lý do hủy hợp đồng..." required></textarea>
                <div class="flex gap-2 mt-3">
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg">Xác nhận hủy</button>
                    <button type="button" onclick="hideHuyForm()" class="px-4 py-2 bg-gray-500 text-white rounded-lg">Hủy</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>

<script>
function showHuyForm() { document.getElementById('huyForm').style.display = 'block'; }
function hideHuyForm() { document.getElementById('huyForm').style.display = 'none'; }
</script>
@endsection