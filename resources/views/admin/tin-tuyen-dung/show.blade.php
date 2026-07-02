@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.tin-tuyen-dung.index') }}" 
               class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Chi tiết tin tuyển dụng
            </h1>
        </div>
        <div class="flex items-center gap-3">
            @if($tinTuyenDung->trang_thai == 'nhap')
                <form action="{{ route('admin.tin-tuyen-dung.publish', $tinTuyenDung->id) }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl font-medium transition text-sm">
                        Đăng tuyển
                    </button>
                </form>
            @elseif($tinTuyenDung->trang_thai == 'dang_tuyen')
                <form action="{{ route('admin.tin-tuyen-dung.stop', $tinTuyenDung->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-xl font-medium transition text-sm">
                        Tạm dừng
                    </button>
                </form>
                <form action="{{ route('admin.tin-tuyen-dung.end', $tinTuyenDung->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl font-medium transition text-sm">
                        Kết thúc
                    </button>
                </form>
            @elseif($tinTuyenDung->trang_thai == 'tam_dung')
                <form action="{{ route('admin.tin-tuyen-dung.publish', $tinTuyenDung->id) }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl font-medium transition text-sm">
                        Đăng lại
                    </button>
                </form>
            @endif
            <a href="{{ route('admin.tin-tuyen-dung.edit', $tinTuyenDung->id) }}" 
               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition text-sm">
                Chỉnh sửa
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="p-4 rounded-xl bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 border border-green-100 dark:border-green-800 text-sm">
            {!! session('success') !!}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 border border-red-100 dark:border-red-800 text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if(session('warning'))
        <div class="p-4 rounded-xl bg-yellow-50 dark:bg-yellow-900/20 text-yellow-700 dark:text-yellow-400 border border-yellow-100 dark:border-yellow-800 text-sm">
            {{ session('warning') }}
        </div>
    @endif

    <!-- Thông tin tin tuyển dụng -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $tinTuyenDung->tieu_de }}</h3>
                    <div class="mt-4 space-y-3">
                        <div class="flex items-start gap-2 text-sm">
                            <span class="text-gray-500 dark:text-gray-400 w-32">Mã tin:</span>
                            <span class="text-gray-900 dark:text-white font-medium">{{ $tinTuyenDung->ma }}</span>
                        </div>
                        <div class="flex items-start gap-2 text-sm">
                            <span class="text-gray-500 dark:text-gray-400 w-32">Phòng ban:</span>
                            <span class="text-gray-900 dark:text-white font-medium">{{ $tinTuyenDung->phongBan?->ten_phong_ban ?? 'Chưa xác định' }}</span>
                        </div>
                        <div class="flex items-start gap-2 text-sm">
                            <span class="text-gray-500 dark:text-gray-400 w-32">Chức vụ:</span>
                            <span class="text-gray-900 dark:text-white font-medium">{{ $tinTuyenDung->chucVu?->ten ?? 'Chưa xác định' }}</span>
                        </div>
                        <div class="flex items-start gap-2 text-sm">
                            <span class="text-gray-500 dark:text-gray-400 w-32">Số lượng:</span>
                            <span class="text-gray-900 dark:text-white font-medium">{{ $tinTuyenDung->so_vi_tri }}</span>
                        </div>
                        <div class="flex items-start gap-2 text-sm">
                            <span class="text-gray-500 dark:text-gray-400 w-32">Loại hợp đồng:</span>
                            <span class="text-gray-900 dark:text-white font-medium">{{ $tinTuyenDung->loai_hop_dong_text }}</span>
                        </div>
                        <div class="flex items-start gap-2 text-sm">
                            <span class="text-gray-500 dark:text-gray-400 w-32">Cấp độ kinh nghiệm:</span>
                            <span class="text-gray-900 dark:text-white font-medium">{{ $tinTuyenDung->cap_do_kinh_nghiem_text }}</span>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="space-y-3">
                        <div class="flex items-start gap-2 text-sm">
                            <span class="text-gray-500 dark:text-gray-400 w-32">Trạng thái:</span>
                            @switch($tinTuyenDung->trang_thai)
                                @case('nhap')
                                    <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400 text-xs font-semibold">Nháp</span>
                                    @break
                                @case('dang_tuyen')
                                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 text-xs font-semibold">Đang tuyển</span>
                                    @break
                                @case('tam_dung')
                                    <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 text-xs font-semibold">Tạm dừng</span>
                                    @break
                                @case('ket_thuc')
                                    <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 text-xs font-semibold">Kết thúc</span>
                                    @break
                                @default
                                    <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400 text-xs font-semibold">{{ $tinTuyenDung->trang_thai }}</span>
                            @endswitch
                        </div>
                        <div class="flex items-start gap-2 text-sm">
                            <span class="text-gray-500 dark:text-gray-400 w-32">Hạn nộp:</span>
                            <span class="text-gray-900 dark:text-white font-medium">{{ $tinTuyenDung->han_nop_ho_so ? $tinTuyenDung->han_nop_ho_so->format('d/m/Y') : '---' }}</span>
                        </div>
                        <div class="flex items-start gap-2 text-sm">
                            <span class="text-gray-500 dark:text-gray-400 w-32">Ngày đăng:</span>
                            <span class="text-gray-900 dark:text-white font-medium">{{ $tinTuyenDung->thoi_gian_dang ? $tinTuyenDung->thoi_gian_dang->format('d/m/Y H:i') : '---' }}</span>
                        </div>
                        <div class="flex items-start gap-2 text-sm">
                            <span class="text-gray-500 dark:text-gray-400 w-32">Người đăng:</span>
                            <span class="text-gray-900 dark:text-white font-medium">
                                @if($tinTuyenDung->nguoiDang)
                                    {{ $tinTuyenDung->nguoiDang->name ?? $tinTuyenDung->nguoiDang->email ?? '---' }}
                                @else
                                    ---
                                @endif
                            </span>
                        </div>
                        <div class="flex items-start gap-2 text-sm">
                            <span class="text-gray-500 dark:text-gray-400 w-32">Làm việc từ xa:</span>
                            <span class="text-gray-900 dark:text-white font-medium">{{ $tinTuyenDung->lam_viec_tu_xa ? 'Có' : 'Không' }}</span>
                        </div>
                        <div class="flex items-start gap-2 text-sm">
                            <span class="text-gray-500 dark:text-gray-400 w-32">Tuyển gấp:</span>
                            <span class="text-gray-900 dark:text-white font-medium">{{ $tinTuyenDung->tuyen_gap ? 'Có' : 'Không' }}</span>
                        </div>
                        @if($tinTuyenDung->luong_toi_thieu || $tinTuyenDung->luong_toi_da)
                        <div class="flex items-start gap-2 text-sm">
                            <span class="text-gray-500 dark:text-gray-400 w-32">Mức lương:</span>
                            <span class="text-gray-900 dark:text-white font-medium">{{ number_format($tinTuyenDung->luong_toi_thieu ?? 0) }} - {{ number_format($tinTuyenDung->luong_toi_da ?? 0) }} VNĐ</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Mô tả công việc -->
            @if($tinTuyenDung->mo_ta_cong_viec)
            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Mô tả công việc</h4>
                <div class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed whitespace-pre-wrap">
                    {{ $tinTuyenDung->mo_ta_cong_viec }}
                </div>
            </div>
            @endif

            <!-- Yêu cầu -->
            @if(!empty($yeuCau))
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Yêu cầu</h4>
                <ul class="list-disc list-inside text-gray-600 dark:text-gray-300 text-sm space-y-1">
                    @foreach($yeuCau as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Phúc lợi -->
            @if(!empty($phucLoi))
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Phúc lợi</h4>
                <ul class="list-disc list-inside text-gray-600 dark:text-gray-300 text-sm space-y-1">
                    @foreach($phucLoi as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Kỹ năng yêu cầu -->
            @if(!empty($kyNang))
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Kỹ năng yêu cầu</h4>
                <div class="flex flex-wrap gap-2">
                    @foreach($kyNang as $item)
                        <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 text-xs font-medium">
                            {{ $item }}
                        </span>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Trình độ học vấn -->
            @if($tinTuyenDung->trinh_do_hoc_van)
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Trình độ học vấn</h4>
                <div class="text-gray-600 dark:text-gray-300 text-sm">
                    {{ $tinTuyenDung->trinh_do_hoc_van }}
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Thống kê ứng viên -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Thống kê ứng viên</h3>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 text-center border border-blue-100 dark:border-blue-800">
                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $thongKe['tong'] }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Tổng</div>
            </div>
            <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-xl p-4 text-center border border-yellow-100 dark:border-yellow-800">
                <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $thongKe['cho_duyet'] }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Chờ duyệt</div>
            </div>
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 text-center border border-blue-100 dark:border-blue-800">
                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $thongKe['da_duyet'] }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Đã duyệt</div>
            </div>
            <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4 text-center border border-green-100 dark:border-green-800">
                <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $thongKe['dat'] }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Trúng tuyển</div>
            </div>
            <div class="bg-red-50 dark:bg-red-900/20 rounded-xl p-4 text-center border border-red-100 dark:border-red-800">
                <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $thongKe['khong_dat'] }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Không đạt</div>
            </div>
        </div>
    </div>

    <!-- Gửi email -->
    @if($tinTuyenDung->ungViens->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Gửi email thông báo cho ứng viên</h3>
        <form method="POST" action="{{ route('admin.tin-tuyen-dung.send-email', $tinTuyenDung->id) }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Tiêu đề email <span class="text-red-500">*</span>
                </label>
                <input type="text" name="tieu_de" 
                       class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm"
                       placeholder="VD: Thông báo kết quả tuyển dụng" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Nội dung email <span class="text-red-500">*</span>
                </label>
                <textarea name="noi_dung" rows="5" 
                          class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm"
                          placeholder="Nhập nội dung email..." required></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Gửi đến ứng viên
                </label>
                <div class="flex flex-wrap gap-4">
                    <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                        <input type="checkbox" name="loai_ung_vien[]" value="moi_nop" checked>
                        Mới nộp ({{ $thongKe['moi_nop'] ?? 0 }})
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                        <input type="checkbox" name="loai_ung_vien[]" value="cho_duyet" checked>
                        Chờ duyệt ({{ $thongKe['cho_duyet'] ?? 0 }})
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                        <input type="checkbox" name="loai_ung_vien[]" value="da_duyet" checked>
                        Đã duyệt ({{ $thongKe['da_duyet'] ?? 0 }})
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                        <input type="checkbox" name="loai_ung_vien[]" value="dat" checked>
                        Trúng tuyển ({{ $thongKe['dat'] ?? 0 }})
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                        <input type="checkbox" name="loai_ung_vien[]" value="khong_dat" checked>
                        Không đạt ({{ $thongKe['khong_dat'] ?? 0 }})
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                        <input type="checkbox" name="loai_ung_vien[]" value="da_huy" checked>
                        Đã hủy ({{ $thongKe['da_huy'] ?? 0 }})
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                        <input type="checkbox" name="loai_ung_vien[]" value="tam_dung" checked>
                        Tạm dừng ({{ $thongKe['tam_dung'] ?? 0 }})
                    </label>
                </div>
            </div>
            <button type="submit" 
                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition shadow-sm">
                Gửi email
            </button>
        </form>
    </div>
    @endif

    <!-- Danh sách ứng viên -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="font-semibold text-gray-900 dark:text-white">Danh sách ứng viên đã ứng tuyển</h3>
            <span class="text-sm text-gray-500 dark:text-gray-400">Tổng: {{ $tinTuyenDung->ungViens->count() }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300 text-xs uppercase">
                        <th class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 text-left">Mã HS</th>
                        <th class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 text-left">Họ tên</th>
                        <th class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 text-left">Email</th>
                        <th class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 text-center">Trạng thái</th>
                        <th class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 text-center">Ngày nộp</th>
                        <th class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tinTuyenDung->ungViens as $ungVien)
                        <tr class="hover:bg-blue-50/40 dark:hover:bg-gray-700/40 transition">
                            <td class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                                <span class="px-3 py-1 rounded-lg bg-gray-100 dark:bg-gray-700 text-xs font-semibold text-gray-900 dark:text-white">
                                    {{ $ungVien->ma_ho_so }}
                                </span>
                            </td>
                            <td class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 font-semibold text-gray-900 dark:text-white">
                                {{ $ungVien->ho }} {{ $ungVien->ten }}
                            </td>
                            <td class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 text-gray-600 dark:text-gray-300">{{ $ungVien->email }}</td>
                            <td class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 text-center">
                                {!! $ungVien->trang_thai_badge !!}
                            </td>
                            <td class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 text-center text-sm text-gray-600 dark:text-gray-300">
                                {{ $ungVien->created_at ? $ungVien->created_at->format('d/m/Y H:i') : '---' }}
                            </td>
                            <td class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 text-center">
                                <a href="{{ route('admin.ung_vien.show', $ungVien->id) }}" 
                                   class="px-3 py-1.5 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-medium hover:bg-blue-100 dark:hover:bg-blue-900/50 transition">
                                    Xem
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <span>Chưa có ứng viên nào ứng tuyển cho tin này</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection