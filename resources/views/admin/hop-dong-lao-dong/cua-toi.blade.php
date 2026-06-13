@extends('layouts.admin')

@section('title', 'Hợp đồng của tôi')

@section('content')
<div class="space-y-6 bg-gray-50 dark:bg-[#0f172a] min-h-screen p-6">

    <div class="bg-white dark:bg-gray-800/80 backdrop-blur border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">📄 Hợp đồng của tôi</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Thông tin hợp đồng lao động của bạn</p>
            </div>
            <a href="{{ route('employee.dashboard') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-xl transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Quay lại
            </a>
        </div>
    </div>

    @if(isset($message))
        <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-400 text-blue-700 px-4 py-3 rounded-xl text-center">{{ $message }}</div>
    @endif

    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 text-green-700 px-4 py-3 rounded-xl">✅ {{ session('success') }}</div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-100 dark:bg-red-900/30 border border-red-400 text-red-700 px-4 py-3 rounded-xl">❌ {{ session('error') }}</div>
    @endif

    @if($hopDong)
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm">
            <div class="border-b px-6 py-4 bg-gray-50"><h3 class="text-lg font-semibold">📋 Thông tin hợp đồng</h3></div>
            <div class="p-6 space-y-3">
                <div class="flex"><div class="w-32 text-gray-500">Số hợp đồng:</div><div class="font-semibold">{{ $hopDong->so_hop_dong }}</div></div>
                <div class="flex"><div class="w-32 text-gray-500">Loại hợp đồng:</div><div><span class="px-2 py-1 rounded-full text-xs bg-blue-100">{{ $hopDong->loai_hop_dong == 'xac_dinh_thoi_han' ? 'Xác định thời hạn' : 'Không xác định' }}</span></div></div>
                <div class="flex"><div class="w-32 text-gray-500">Ngày bắt đầu:</div><div>{{ $hopDong->ngay_bat_dau->format('d/m/Y') }}</div></div>
                <div class="flex"><div class="w-32 text-gray-500">Ngày kết thúc:</div><div>{{ $hopDong->ngay_ket_thuc ? $hopDong->ngay_ket_thuc->format('d/m/Y') : '---' }}</div></div>
                <div class="flex"><div class="w-32 text-gray-500">Lương:</div><div class="font-semibold text-green-600">{{ number_format($hopDong->luong_co_ban, 0, ',', '.') }} đ</div></div>
                <div class="flex"><div class="w-32 text-gray-500">Phụ cấp:</div><div>{{ number_format($hopDong->phu_cap, 0, ',', '.') }} đ</div></div>
                <div class="flex"><div class="w-32 text-gray-500">Địa điểm:</div><div>{{ $hopDong->dia_diem_lam_viec }}</div></div>
                <div class="flex"><div class="w-32 text-gray-500">Trạng thái:</div><div><span class="px-2 py-1 rounded-full text-xs {{ $hopDong->trang_thai_hop_dong == 'hieu_luc' ? 'bg-green-100 text-green-700' : ($hopDong->trang_thai_hop_dong == 'chua_hieu_luc' ? 'bg-yellow-100 text-yellow-700' : 'bg-orange-100 text-orange-700') }}">{{ ucfirst(str_replace('_', ' ', $hopDong->trang_thai_hop_dong)) }}</span></div></div>
                <div class="flex"><div class="w-32 text-gray-500">Trạng thái ký:</div><div><span class="px-2 py-1 rounded-full text-xs {{ $hopDong->trang_thai_ky == 'da_ky' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">{{ ucfirst(str_replace('_', ' ', $hopDong->trang_thai_ky)) }}</span></div></div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm">
            <div class="border-b px-6 py-4 bg-gray-50"><h3 class="text-lg font-semibold">👤 Thông tin nhân viên</h3></div>
            <div class="p-6 space-y-3">
                <div class="flex"><div class="w-32 text-gray-500">Họ tên:</div><div>{{ $hopDong->hoSoNguoiDung ? ($hopDong->hoSoNguoiDung->ho . ' ' . $hopDong->hoSoNguoiDung->ten) : 'N/A' }}</div></div>
                <div class="flex"><div class="w-32 text-gray-500">Mã NV:</div><div>{{ $hopDong->hoSoNguoiDung ? $hopDong->hoSoNguoiDung->ma_nhan_vien : 'N/A' }}</div></div>
                <div class="flex"><div class="w-32 text-gray-500">Chức vụ:</div><div>{{ $hopDong->chucVu ? $hopDong->chucVu->ten : 'N/A' }}</div></div>
                <div class="flex"><div class="w-32 text-gray-500">Phòng ban:</div><div>{{ $hopDong->nguoiDung->phongBan->ten_phong_ban ?? 'N/A' }}</div></div>
            </div>
        </div>
    </div>

    @if($hopDong->duong_dan_file)
    <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm">
        <div class="border-b px-6 py-4 bg-gray-50"><h3 class="text-lg font-semibold text-blue-600">📎 File hợp đồng gốc</h3></div>
        <div class="p-6">
            @foreach(array_filter(explode(';', $hopDong->duong_dan_file)) as $file)
                <a href="{{ asset('storage/' . trim($file)) }}" target="_blank" class="inline-flex items-center gap-2 p-2 rounded-lg bg-blue-50 hover:bg-blue-100 mr-2">📄 {{ basename(trim($file)) }}</a>
            @endforeach
        </div>
    </div>
    @endif

    @if($hopDong->trang_thai_ky == 'cho_ky' && in_array($hopDong->trang_thai_hop_dong, ['hieu_luc', 'chua_hieu_luc', 'het_han']))
    <div class="bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-400 rounded-xl p-6 text-center">
        <p class="mb-4">Hợp đồng chưa được ký. Vui lòng ký hợp đồng để hoàn tất.</p>
        <div class="flex justify-center gap-3">
            <a href="{{ route('admin.hop-dong.ky', $hopDong->id) }}" class="px-6 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700">✍️ Ký hợp đồng ngay</a>
            <button onclick="showTuChoiForm()" class="px-6 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700">❌ Từ chối ký</button>
        </div>
        <div id="tuChoiForm" style="display:none;" class="mt-4 p-4 bg-white rounded-lg">
            <form action="{{ route('admin.hop-dong.tu-choi-ky', $hopDong->id) }}" method="POST">
                @csrf
                <textarea name="ly_do_tu_choi" class="w-full p-3 border rounded-lg" rows="3" placeholder="Nhập lý do từ chối ký..." required></textarea>
                <div class="flex gap-2 mt-3">
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg">Gửi lý do từ chối</button>
                    <button type="button" onclick="hideTuChoiForm()" class="px-4 py-2 bg-gray-500 text-white rounded-lg">Hủy</button>
                </div>
            </form>
        </div>
    </div>
    @endif
    @else
    <div class="text-center py-12">
        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        <p class="text-gray-500">Bạn chưa có hợp đồng nào được HR gửi</p>
    </div>
    @endif
</div>

<script>
function showTuChoiForm() { document.getElementById('tuChoiForm').style.display = 'block'; }
function hideTuChoiForm() { document.getElementById('tuChoiForm').style.display = 'none'; }
</script>
@endsection