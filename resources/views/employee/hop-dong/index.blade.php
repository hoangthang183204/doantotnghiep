@extends('layouts.employee')

@section('content')
    <div class="p-6 max-w-7xl mx-auto space-y-6">
        @if(session('success'))
            <div class="p-4 mb-2 text-sm text-green-800 rounded-xl bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-200 dark:border-green-800 flex items-center">
                <i class="fas fa-check-circle mr-2 text-base"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 mb-2 text-sm text-red-800 rounded-xl bg-red-50 dark:bg-gray-800 dark:text-red-400 border border-red-200 dark:border-red-800 flex items-center">
                <i class="fas fa-exclamation-circle mr-2 text-base"></i>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-4 border-b border-gray-200 dark:border-gray-700">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Hợp Đồng Của Tôi</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Xem và quản lý thông tin hợp đồng lao động cá nhân của bạn.</p>
            </div>
            <button onclick="window.history.back();"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors shadow-sm dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i> Quay lại
            </button>
        </div>

        @if (!$hopDong)
            <div class="flex flex-col items-center justify-center p-12 text-center bg-yellow-50 rounded-xl border border-yellow-100 dark:bg-gray-800 dark:border-yellow-700">
                <div class="p-3 bg-yellow-100 dark:bg-gray-700 rounded-full text-yellow-600 dark:text-yellow-400 mb-4">
                    <i class="fas fa-exclamation-circle text-2xl"></i>
                </div>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">Chưa tìm thấy dữ liệu</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm mt-1">Bạn hiện tại chưa có hợp đồng lao động nào được cập nhật trên hệ thống này.</p>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-5 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 flex items-center">
                            <i class="fas fa-file-contract text-blue-600 dark:text-blue-400 text-lg mr-3"></i>
                            <h4 class="font-bold text-gray-800 dark:text-gray-200">Thông tin hợp đồng</h4>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                            <div class="space-y-4">
                                <div>
                                    <span class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Số hợp đồng:</span>
                                    <span class="text-base font-semibold text-gray-900 dark:text-white">{{ $hopDong->so_hop_dong ?? 'Chưa cập nhật' }}</span>
                                </div>
                                <div>
                                    <span class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider block mb-1">Loại hợp đồng:</span>
                                    @if (($hopDong->loai_hop_dong ?? '') == 'khong_xac_dinh_thoi_han')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800">Không xác định thời hạn</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-400 dark:border-indigo-800">Xác định thời hạn</span>
                                    @endif
                                </div>
                                <div>
                                    <span class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Ngày bắt đầu:</span>
                                    <span class="text-base font-medium text-gray-900 dark:text-white">{{ isset($hopDong->ngay_bat_dau) ? date('d/m/Y', strtotime($hopDong->ngay_bat_dau)) : 'Chưa cập nhật' }}</span>
                                </div>
                                <div>
                                    <span class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Ngày kết thúc:</span>
                                    <span class="text-base font-medium text-gray-900 dark:text-white">{{ isset($hopDong->ngay_ket_thuc) ? date('d/m/Y', strtotime($hopDong->ngay_ket_thuc)) : 'Vô thời hạn' }}</span>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <span class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider block mb-1">Trạng thái hợp đồng:</span>
                                    @if (($hopDong->trang_thai_hop_dong ?? '') == 'hieu_luc')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-green-50 text-green-700 border border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800">Còn hiệu lực</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-red-50 text-red-700 border border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800">Hết hạn</span>
                                    @endif
                                </div>
                                <div>
                                    <span class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider block mb-1">Trạng thái ký:</span>
                                    @if (($hopDong->trang_thai_ky ?? '') == 'da_ky')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800">Đã ký</span>
                                    @elseif (($hopDong->trang_thai_ky ?? '') == 'tu_choi')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-900/30 dark:text-rose-400 dark:border-rose-800">Từ chối ký</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800">Chưa ký</span>
                                    @endif
                                </div>
                                <div>
                                    <span class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Hình thức làm việc:</span>
                                    <span class="text-base font-medium text-gray-900 dark:text-white">{{ ($hopDong->hinh_thuc_lam_viec ?? '') == 'full_time' ? 'Toàn thời gian (Full-time)' : 'Bán thời gian (Part-time)' }}</span>
                                </div>
                                <div>
                                    <span class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Địa điểm làm việc:</span>
                                    <span class="text-base font-medium text-gray-900 dark:text-white">{{ $hopDong->dia_diem_lam_viec ?? 'Hà Nội' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-5 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 flex items-center">
                            <i class="fas fa-money-bill-wave text-green-600 dark:text-green-400 text-lg mr-3"></i>
                            <h4 class="font-bold text-gray-800 dark:text-gray-200">Thông tin lương & Chế độ phụ cấp</h4>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="pb-4 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider block mb-1">Lương cơ bản:</span>
                                <span class="text-3xl font-bold text-green-600 dark:text-green-400">
                                    {{ number_format($hopDong->luong_co_ban ?? 0, 0, ',', '.') }} <span class="text-sm font-normal text-gray-500">VNĐ</span>
                                </span>
                            </div>

                            <div>
                                <span class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider block mb-3">Danh sách phụ cấp đi kèm hợp đồng:</span>
                                @if(isset($dsPhuCap) && count($dsPhuCap) > 0)
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        @foreach($dsPhuCap as $phuCap)
                                            <div class="flex items-center justify-between p-3.5 bg-blue-50/40 dark:bg-gray-700/30 rounded-xl border border-blue-100/50 dark:border-gray-700 transition-all hover:shadow-sm">
                                                <div class="flex items-center space-x-3">
                                                    <div class="p-2 bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-lg">
                                                        <i class="fas fa-gift text-sm"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $phuCap->ten }}</p>
                                                        @if(!empty($phuCap->mo_ta))
                                                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $phuCap->mo_ta }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <span class="text-base font-bold text-blue-600 dark:text-blue-400">
                                                    {{ number_format($phuCap->so_tien_mac_dinh ?? 0, 0, ',', '.') }} <span class="text-xs font-normal text-gray-400">đ</span>
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="p-4 bg-gray-50 dark:bg-gray-900/20 text-gray-500 dark:text-gray-400 rounded-xl border border-gray-100 dark:border-gray-800 text-sm italic flex items-center">
                                        <i class="fas fa-info-circle mr-2 text-gray-400"></i> Hợp đồng này không bao gồm các khoản phụ cấp thêm.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-5 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 flex items-center">
                            <i class="fas fa-sticky-note text-amber-500 text-lg mr-3"></i>
                            <h4 class="font-bold text-gray-800 dark:text-gray-200">Ghi chú</h4>
                        </div>
                        <div class="p-6">
                            <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed bg-gray-50 dark:bg-gray-900/40 p-4 rounded-lg border border-gray-100 dark:border-gray-800">
                                {{ $hopDong->ghi_chu ?? 'Hợp đồng 1 năm, có thể gia hạn theo thỏa thuận.' }}
                            </p>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-5 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 flex items-center">
                            <i class="fas fa-paperclip text-red-500 text-lg mr-3"></i>
                            <h4 class="font-bold text-gray-800 dark:text-gray-200">File hợp đồng gốc</h4>
                        </div>
                        <div class="p-6">
                            @if (!empty($hopDong->file_hop_dong))
                                <div class="p-2">
                                    <a href="{{ asset('pdf/HD0004.pdf') }}" download
                                        class="inline-flex items-center px-4 py-2.5 bg-red-50 text-red-700 border border-red-200 rounded-lg hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:border-red-800 transition-colors text-sm font-medium">
                                        <i class="fas fa-file-pdf text-base mr-3"></i> Tải xuống file hợp đồng gốc
                                    </a>
                                </div>
                            @else
                                <a href="{{ asset('pdf/HD0004.pdf') }}" download
                                    class="text-blue-600 hover:underline flex items-center">
                                    <i class="fas fa-file-pdf mr-2"></i> File 1: {{ $hopDong->so_hop_dong ?? 'HD0004' }}..pdf
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-5 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 flex items-center">
                            <i class="fas fa-user-circle text-teal-600 dark:text-teal-400 text-lg mr-3"></i>
                            <h4 class="font-bold text-gray-800 dark:text-gray-200">Thông tin nhân viên</h4>
                        </div>
                        <div class="p-6">
                            <div class="flex flex-col items-center text-center pb-6 border-b border-gray-100 dark:border-gray-700">
                                <div class="w-20 h-20 bg-gradient-to-tr from-blue-500 to-teal-400 rounded-full flex items-center justify-center text-white text-2xl font-bold shadow-md ring-4 ring-white dark:ring-gray-800">
                                    @if (!empty($hopDong->nhan_vien_ho_ten))
                                        {{ mb_substr(strrchr($hopDong->nhan_vien_ho_ten, ' '), 1) ?: mb_substr($hopDong->nhan_vien_ho_ten, 0, 2) }}
                                    @else
                                        NV
                                    @endif
                                </div>
                                <h5 class="text-lg font-bold text-gray-900 dark:text-white mt-4 mb-0.5">{{ $hopDong->nhan_vien_ho_ten ?? $hopDong->ten_dang_nhap }}</h5>
                                <span class="text-xs font-semibold px-2.5 py-0.5 bg-gray-100 text-gray-800 rounded-full dark:bg-gray-700 dark:text-gray-300 mt-1">Mã NV: {{ $hopDong->nhan_vien_ma_nv ?? 'Chưa cấp' }}</span>
                            </div>

                            <div class="pt-6 space-y-4">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-400 dark:text-gray-500">Chức vụ:</span>
                                    <span class="font-semibold text-gray-800 dark:text-gray-200 text-right">{{ $hopDong->ten_chuc_vu ?? 'Chưa cập nhật' }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-400 dark:text-gray-500">Phòng ban:</span>
                                    <span class="font-semibold text-gray-800 dark:text-gray-200 text-right">{{ $hopDong->ten_phong_ban ?? 'Chưa xếp phòng' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden sticky top-6">
                        <div class="px-5 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 flex items-center">
                            <i class="fas fa-pen-nib text-indigo-600 dark:text-indigo-400 text-lg mr-3"></i>
                            <h4 class="font-bold text-gray-800 dark:text-gray-200">Xác nhận ký kết</h4>
                        </div>
                        <div class="p-6 space-y-4">
                            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                                Vui lòng đọc kỹ toàn bộ điều khoản hợp đồng lao động, mức lương cùng danh sách phụ cấp được hưởng trước khi đưa ra quyết định ký duyệt điện tử.
                            </p>

                            @if (($hopDong->trang_thai_ky ?? '') == 'da_ky')
                                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 rounded-xl border border-emerald-200 dark:border-emerald-900 text-center font-bold text-sm">
                                    <i class="fas fa-check-double text-base mr-1.5 animate-bounce"></i> Hợp đồng đã được ký kết thành công
                                </div>
                            @elseif (($hopDong->trang_thai_ky ?? '') == 'tu_choi')
                                <div class="p-4 bg-rose-50 dark:bg-rose-950/20 text-rose-700 dark:text-rose-400 rounded-xl border border-rose-200 dark:border-rose-900 text-center font-bold text-sm">
                                    <i class="fas fa-times-circle text-base mr-1.5"></i> Bạn đã từ chối ký hợp đồng này
                                </div>
                            @else
                                <form action="{{ route('employee.hopdong.update-status', $hopDong->id) }}" method="POST" class="space-y-3 pt-2">
                                    @csrf
                                    @method('PATCH')
                                    
                                    <button type="submit" name="action" value="da_ky" 
                                        onclick="return confirm('Bạn hoàn toàn đồng ý với các mức lương, phụ cấp và điều khoản và muốn tiến hành KÝ hợp đồng này chứ?')"
                                        class="w-full inline-flex items-center justify-center px-4 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold shadow-sm transition-colors text-sm">
                                        <i class="fas fa-file-signature mr-2 text-base"></i> Ký kết hợp đồng
                                    </button>
                                    
                                    <button type="submit" name="action" value="tu_choi" 
                                        onclick="return confirm('Bạn chắc chắn muốn TỪ CHỐI ký kết hợp đồng lao động này?')"
                                        class="w-full inline-flex items-center justify-center px-4 py-3 bg-white dark:bg-gray-800 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-900/60 rounded-xl font-medium hover:bg-rose-50 dark:hover:bg-rose-950/20 transition-colors text-sm">
                                        <i class="fas fa-times mr-2"></i> Từ chối ký kết
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection