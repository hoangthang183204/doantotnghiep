@extends('layouts.admin')

@section('title', 'Ký hợp đồng')

@section('content')
<div class="space-y-6 bg-gray-50 dark:bg-[#0f172a] min-h-screen p-6">

    <div class="bg-white dark:bg-gray-800/80 backdrop-blur border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
        <div class="flex justify-between items-center">
            <div><h1 class="text-2xl font-bold text-gray-900 dark:text-white">✍️ Ký hợp đồng lao động</h1><p class="text-gray-500 mt-1">Vui lòng xem xét và ký hợp đồng của bạn</p></div>
            <a href="{{ route('admin.hop-dong.cua-toi') }}" class="px-4 py-2 bg-gray-500 text-white rounded-xl">← Quay lại</a>
        </div>
    </div>

    @if(session('error'))<div class="bg-red-100 border border-red-400 text-red-700 p-3 rounded-xl">❌ {{ session('error') }}</div>@endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white border rounded-xl p-6">
            <h3 class="text-lg font-semibold mb-4">📋 Thông tin hợp đồng</h3>
            <div class="space-y-2">
                <div class="flex"><span class="w-32 text-gray-500">Số hợp đồng:</span><span class="font-semibold">{{ $hopDong->so_hop_dong }}</span></div>
                <div class="flex"><span class="w-32 text-gray-500">Loại hợp đồng:</span><span>{{ $hopDong->loai_hop_dong == 'xac_dinh_thoi_han' ? 'Xác định thời hạn' : 'Không xác định' }}</span></div>
                <div class="flex"><span class="w-32 text-gray-500">Ngày bắt đầu:</span><span>{{ $hopDong->ngay_bat_dau->format('d/m/Y') }}</span></div>
                <div class="flex"><span class="w-32 text-gray-500">Ngày kết thúc:</span><span>{{ $hopDong->ngay_ket_thuc ? $hopDong->ngay_ket_thuc->format('d/m/Y') : '---' }}</span></div>
                <div class="flex"><span class="w-32 text-gray-500">Lương:</span><span class="text-green-600 font-semibold">{{ number_format($hopDong->luong_co_ban, 0, ',', '.') }} đ</span></div>
                <div class="flex"><span class="w-32 text-gray-500">Phụ cấp:</span><span>{{ number_format($hopDong->phu_cap, 0, ',', '.') }} đ</span></div>
                <div class="flex"><span class="w-32 text-gray-500">Địa điểm:</span><span>{{ $hopDong->dia_diem_lam_viec }}</span></div>
            </div>
        </div>

        <div class="bg-white border rounded-xl p-6">
            <h3 class="text-lg font-semibold mb-4">👤 Thông tin nhân viên</h3>
            <div class="space-y-2">
                <div class="flex"><span class="w-32 text-gray-500">Họ tên:</span><span>{{ $hopDong->hoSoNguoiDung ? ($hopDong->hoSoNguoiDung->ho . ' ' . $hopDong->hoSoNguoiDung->ten) : 'N/A' }}</span></div>
                <div class="flex"><span class="w-32 text-gray-500">Mã NV:</span><span>{{ $hopDong->hoSoNguoiDung ? $hopDong->hoSoNguoiDung->ma_nhan_vien : 'N/A' }}</span></div>
                <div class="flex"><span class="w-32 text-gray-500">Chức vụ:</span><span>{{ $hopDong->chucVu ? $hopDong->chucVu->ten : 'N/A' }}</span></div>
            </div>
        </div>
    </div>

    @if($hopDong->duong_dan_file)
    <div class="bg-white border rounded-xl p-6">
        <h3 class="text-lg font-semibold text-blue-600 mb-3">📎 File hợp đồng gốc</h3>
        @foreach(array_filter(explode(';', $hopDong->duong_dan_file)) as $file)
            <a href="{{ asset('storage/' . trim($file)) }}" target="_blank" class="inline-flex items-center gap-2 p-2 bg-blue-50 rounded-lg mr-2">📄 {{ basename(trim($file)) }}</a>
        @endforeach
    </div>
    @endif

    <div class="bg-white border rounded-xl p-6">
        <h3 class="text-lg font-semibold text-green-600 mb-3">✍️ Ký hợp đồng</h3>
        <form action="{{ route('admin.hop-dong.xu-ly-ky', $hopDong->id) }}" method="POST" enctype="multipart/form-data" id="kyForm">
            @csrf
            <label class="block font-medium mb-2">Upload file hợp đồng đã ký <span class="text-red-500">*</span></label>
            <input type="file" name="file_hop_dong_da_ky[]" multiple accept=".pdf,.jpg,.jpeg,.png" class="w-full p-2 border rounded-lg" required>
            <p class="text-sm text-gray-500 mt-1">Chấp nhận PDF, JPG, PNG. Tối đa 10MB mỗi file.</p>
            <div id="fileList" class="mt-3 space-y-2"></div>
            <div class="flex gap-3 mt-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700" onclick="return confirm('Xác nhận ký hợp đồng?')">✅ Xác nhận ký</button>
                <a href="{{ route('admin.hop-dong.cua-toi') }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg">← Quay lại</a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('kyForm')?.addEventListener('submit', function(e) {
    const files = document.querySelector('input[name="file_hop_dong_da_ky[]"]').files;
    if (files.length === 0) { e.preventDefault(); alert('Vui lòng upload file hợp đồng đã ký!'); return false; }
    for (let f of files) { if (f.size > 10 * 1024 * 1024) { e.preventDefault(); alert(`File "${f.name}" quá 10MB!`); return false; } }
    const allowed = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
    for (let f of files) { if (!allowed.includes(f.type)) { e.preventDefault(); alert(`File "${f.name}" không đúng định dạng!`); return false; } }
    if (!confirm('Bạn có chắc chắn muốn ký hợp đồng này?')) e.preventDefault();
});
</script>
@endsection