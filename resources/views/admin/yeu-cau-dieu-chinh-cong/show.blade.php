@extends('layouts.admin')

@section('title', 'Chi tiết yêu cầu điều chỉnh công')

@section('content')

<div class="space-y-6 bg-gray-50 dark:bg-[#0f172a] min-h-screen p-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800/80 backdrop-blur border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    Chi tiết yêu cầu điều chỉnh công
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">
                    Thông tin chi tiết yêu cầu #{{ $yeuCau->id }}
                </p>
            </div>
            <div>
                <a href="{{ route('admin.yeu-cau-dieu-chinh-cong.index') }}" 
                    class="px-5 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Quay lại
                </a>
            </div>
        </div>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex justify-between items-center">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="font-bold text-xl">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 dark:bg-red-900/30 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- THÔNG TIN YÊU CẦU --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Thông tin yêu cầu</h3>
                    @php
                        $statusClass = match($yeuCau->trang_thai) {
                            'cho_duyet' => 'bg-yellow-100 text-yellow-700',
                            'da_duyet' => 'bg-green-100 text-green-700',
                            'tu_choi' => 'bg-red-100 text-red-700',
                            default => 'bg-gray-100 text-gray-700',
                        };
                        $statusText = match($yeuCau->trang_thai) {
                            'cho_duyet' => 'Chờ duyệt',
                            'da_duyet' => 'Đã duyệt',
                            'tu_choi' => 'Từ chối',
                            default => $yeuCau->trang_thai,
                        };
                    @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">{{ $statusText }}</span>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-3">THÔNG TIN NHÂN VIÊN</h4>
                            <div class="space-y-2">
                                <div class="flex"><span class="w-28 text-gray-500">Họ tên:</span><span class="font-medium">{{ optional($yeuCau->nguoiDung->hoSo)->ho ?? '' }} {{ optional($yeuCau->nguoiDung->hoSo)->ten ?? '' }}</span></div>
                                <div class="flex"><span class="w-28 text-gray-500">Mã NV:</span><span class="font-medium">{{ optional($yeuCau->nguoiDung->hoSo)->ma_nhan_vien ?? 'N/A' }}</span></div>
                                <div class="flex"><span class="w-28 text-gray-500">Phòng ban:</span><span class="font-medium">{{ optional($yeuCau->nguoiDung->phongBan)->ten_phong_ban ?? 'N/A' }}</span></div>
                                <div class="flex"><span class="w-28 text-gray-500">Email:</span><span>{{ $yeuCau->nguoiDung->email ?? 'N/A' }}</span></div>
                                <div class="flex"><span class="w-28 text-gray-500">SĐT:</span><span>{{ optional($yeuCau->nguoiDung->hoSo)->so_dien_thoai ?? 'N/A' }}</span></div>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-3">CHI TIẾT ĐIỀU CHỈNH</h4>
                            <div class="space-y-2">
                                <div class="flex"><span class="w-28 text-gray-500">Ngày:</span><span class="font-medium">{{ \Carbon\Carbon::parse($yeuCau->ngay)->format('d/m/Y') }}</span></div>
                                <div class="flex"><span class="w-28 text-gray-500">Giờ vào:</span><span class="text-blue-600 font-medium">{{ $yeuCau->gio_vao ? \Carbon\Carbon::parse($yeuCau->gio_vao)->format('H:i') : '--:--' }}</span></div>
                                <div class="flex"><span class="w-28 text-gray-500">Giờ ra:</span><span class="text-blue-600 font-medium">{{ $yeuCau->gio_ra ? \Carbon\Carbon::parse($yeuCau->gio_ra)->format('H:i') : '--:--' }}</span></div>
                                <div class="flex"><span class="w-28 text-gray-500">Ngày tạo:</span><span>{{ $yeuCau->created_at->format('d/m/Y H:i') }}</span></div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4 border-gray-200 dark:border-gray-700">

                    <div>
                        <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-2">LÝ DO ĐIỀU CHỈNH</h4>
                        <div class="p-3 bg-gray-100 dark:bg-gray-700/50 rounded-lg">
                            {{ $yeuCau->ly_do }}
                        </div>
                    </div>

                    @if($yeuCau->tep_dinh_kem)
                        <div class="mt-4">
                            <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-2">FILE ĐÍNH KÈM</h4>
                            <div class="p-3 bg-gray-100 dark:bg-gray-700/50 rounded-lg flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                </svg>
                                <a href="{{ route('admin.yeu-cau-dieu-chinh-cong.download', $yeuCau->id) }}" class="text-blue-600 hover:underline">
                                    {{ basename($yeuCau->tep_dinh_kem) }}
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- THÔNG TIN DUYỆT & XỬ LÝ --}}
        <div class="lg:col-span-1">
            {{-- Trạng thái duyệt --}}
            <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Trạng thái duyệt</h3>
                <div class="text-center">
                    @if($yeuCau->trang_thai === 'cho_duyet')
                        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-yellow-600 font-semibold">Chờ duyệt</p>
                        <p class="text-sm text-gray-500 mt-1">Yêu cầu đang chờ được xử lý</p>
                    @elseif($yeuCau->trang_thai === 'da_duyet')
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-green-600 font-semibold">Đã duyệt</p>
                        <p class="text-sm text-gray-500 mt-1">
                            Bởi: {{ optional($yeuCau->nguoiDuyet->hoSo)->ho ?? '' }} {{ optional($yeuCau->nguoiDuyet->hoSo)->ten ?? '' }}<br>
                            {{ $yeuCau->duyet_vao ? \Carbon\Carbon::parse($yeuCau->duyet_vao)->format('d/m/Y H:i') : '' }}
                        </p>
                    @else
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-red-600 font-semibold">Từ chối</p>
                        <p class="text-sm text-gray-500 mt-1">
                            Bởi: {{ optional($yeuCau->nguoiDuyet->hoSo)->ho ?? '' }} {{ optional($yeuCau->nguoiDuyet->hoSo)->ten ?? '' }}<br>
                            {{ $yeuCau->duyet_vao ? \Carbon\Carbon::parse($yeuCau->duyet_vao)->format('d/m/Y H:i') : '' }}
                        </p>
                    @endif

                    @if($yeuCau->ghi_chu_duyet)
                        <hr class="my-3">
                        <p class="text-sm text-gray-500 mt-2"><strong>Ghi chú:</strong> {{ $yeuCau->ghi_chu_duyet }}</p>
                    @endif
                </div>
            </div>

            {{-- Form duyệt (chỉ hiển thị khi chờ duyệt) --}}
            @if($yeuCau->trang_thai === 'cho_duyet')
                <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Xử lý yêu cầu</h3>
                    <form action="{{ route('admin.yeu-cau-dieu-chinh-cong.duyet', $yeuCau->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hành động</label>
                            <select name="hanh_dong" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800" required>
                                <option value="">-- Chọn hành động --</option>
                                <option value="duyet">✅ Duyệt yêu cầu</option>
                                <option value="tu_choi">❌ Từ chối yêu cầu</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ghi chú</label>
                            <textarea name="ghi_chu_duyet" rows="3" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800" placeholder="Nhập ghi chú (không bắt buộc)..."></textarea>
                        </div>
                        <button type="submit" class="w-full px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                            Xác nhận xử lý
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection