{{-- resources/views/employee/tang-ca/create.blade.php --}}
@extends('layouts.employee')

@section('title', 'Gửi kiến nghị tăng ca')

@section('content')
<div class="space-y-6 max-w-full px-4 sm:px-6">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                <i class="fas fa-paper-plane mr-3 text-blue-600"></i>
                Gửi kiến nghị tăng ca
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Gửi kiến nghị đến trưởng phòng để xem xét</p>
        </div>
        <a href="{{ route('employee.tang-ca.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition whitespace-nowrap">
            ← Quay lại
        </a>
    </div>

    {{-- THÔNG TIN GIỚI HẠN --}}
    @php
        $thongKeGio = App\Helpers\OvertimeHelper::thongKeGioTangCa(Auth::id());
    @endphp
    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg border border-blue-200 dark:border-blue-800">
            <div class="flex items-center justify-between">
                <p class="text-xs text-blue-600 dark:text-blue-400">Đã dùng tháng</p>
                <span class="text-xs font-bold text-blue-700 dark:text-blue-300">
                    {{ $thongKeGio['trong_thang_text'] }}
                </span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                <div class="bg-blue-600 h-1.5 rounded-full" 
                     style="width: {{ min(100, ($thongKeGio['trong_thang'] / $thongKeGio['limit_month']) * 100) }}%"></div>
            </div>
            <p class="text-[10px] text-gray-500 mt-0.5">Giới hạn: {{ $thongKeGio['limit_month_text'] }}</p>
        </div>
        
        <div class="bg-green-50 dark:bg-green-900/20 p-3 rounded-lg border border-green-200 dark:border-green-800">
            <div class="flex items-center justify-between">
                <p class="text-xs text-green-600 dark:text-green-400">Đã dùng năm</p>
                <span class="text-xs font-bold text-green-700 dark:text-green-300">
                    {{ $thongKeGio['trong_nam_text'] }}
                </span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                <div class="bg-green-600 h-1.5 rounded-full" 
                     style="width: {{ min(100, ($thongKeGio['trong_nam'] / $thongKeGio['limit_year']) * 100) }}%"></div>
            </div>
            <p class="text-[10px] text-gray-500 mt-0.5">Giới hạn: {{ $thongKeGio['limit_year_text'] }}</p>
        </div>
        
        <div class="bg-yellow-50 dark:bg-yellow-900/20 p-3 rounded-lg border border-yellow-200 dark:border-yellow-800">
            <div class="flex items-center justify-between">
                <p class="text-xs text-yellow-600 dark:text-yellow-400">Còn lại tháng</p>
                <span class="text-xs font-bold text-yellow-700 dark:text-yellow-300">
                    {{ $thongKeGio['remaining_month_text'] }}
                </span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                <div class="bg-yellow-600 h-1.5 rounded-full" 
                     style="width: {{ min(100, ($thongKeGio['remaining_month'] / $thongKeGio['limit_month']) * 100) }}%"></div>
            </div>
            <p class="text-[10px] text-gray-500 mt-0.5">Còn {{ $thongKeGio['remaining_month_text'] }}</p>
        </div>
        
        <div class="bg-purple-50 dark:bg-purple-900/20 p-3 rounded-lg border border-purple-200 dark:border-purple-800">
            <div class="flex items-center justify-between">
                <p class="text-xs text-purple-600 dark:text-purple-400">Còn lại năm</p>
                <span class="text-xs font-bold text-purple-700 dark:text-purple-300">
                    {{ $thongKeGio['remaining_year_text'] }}
                </span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                <div class="bg-purple-600 h-1.5 rounded-full" 
                     style="width: {{ min(100, ($thongKeGio['remaining_year'] / $thongKeGio['limit_year']) * 100) }}%"></div>
            </div>
            <p class="text-[10px] text-gray-500 mt-0.5">Còn {{ $thongKeGio['remaining_year_text'] }}</p>
        </div>
    </div>

    {{-- QUY TRÌNH --}}
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
        <div class="flex items-start gap-3">
            <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 mt-0.5"></i>
            <div>
                <p class="text-sm font-medium text-blue-700 dark:text-blue-400">
                    📌 Quy trình kiến nghị tăng ca
                </p>
                <ul class="text-xs text-blue-600 dark:text-blue-300 mt-1 space-y-1 list-disc list-inside">
                    <li>Gửi <strong>kiến nghị</strong> đến Trưởng phòng để xem xét</li>
                    <li>Trưởng phòng sẽ <strong>đồng ý</strong> hoặc <strong>từ chối</strong> kiến nghị</li>
                    <li>Nếu đồng ý, Trưởng phòng sẽ <strong>tạo đơn tăng ca chính thức</strong> cho bạn</li>
                    <li>Bạn sẽ nhận thông báo khi có đơn tăng ca</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- FORM KIẾN NGHỊ --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <form action="{{ route('employee.tang-ca.store') }}" method="POST" id="kienNghiForm">
            @csrf
            <div class="p-6 space-y-4">
                
                {{-- Lý do đề nghị --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Lý do đề nghị <span class="text-red-500">*</span>
                    </label>
                    <textarea name="ly_do_tang_ca" id="lyDo" rows="6" 
                              class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none" 
                              placeholder="Nhập lý do đề nghị tăng ca (tối thiểu 10 ký tự)..." required>{{ old('ly_do_tang_ca') }}</textarea>
                    <div class="flex justify-between mt-2">
                        <span class="text-xs text-gray-400">Tối thiểu 10 ký tự</span>
                        <span id="lyDoCount" class="text-xs text-gray-400">0/500</span>
                    </div>
                    @error('ly_do_tang_ca')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Lưu ý --}}
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-3">
                    <p class="text-xs text-yellow-700 dark:text-yellow-400">
                        <i class="fas fa-info-circle mr-1"></i>
                        <strong>Lưu ý:</strong> Bạn chỉ cần nhập lý do muốn tăng ca. Trưởng phòng sẽ xem xét và tạo đơn tăng ca chính thức cho bạn nếu đồng ý.
                    </p>
                </div>

                {{-- Nút --}}
                <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-paper-plane"></i>
                        Gửi kiến nghị
                    </button>
                    <a href="{{ route('employee.tang-ca.index') }}" class="px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                        Hủy
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- THÔNG TIN TRƯỞNG PHÒNG --}}
    @php
        $truongPhong = App\Models\NguoiDung::whereHas('vaiTros', function($q) {
            $q->whereIn('name', ['truong_phong', 'quan_ly', 'manager']);
        })->first();
    @endphp
    @if($truongPhong)
    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400">
                <i class="fas fa-user-tie"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    Kiến nghị sẽ được gửi đến:
                </p>
                <p class="font-semibold text-gray-900 dark:text-white">
                    {{ optional($truongPhong->hoSo)->ho ?? '' }} {{ optional($truongPhong->hoSo)->ten ?? $truongPhong->ten_dang_nhap }}
                    <span class="text-sm font-normal text-gray-500">(Trưởng phòng)</span>
                </p>
                <p class="text-xs text-gray-400">{{ $truongPhong->email }}</p>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('lyDo').addEventListener('input', function() {
        document.getElementById('lyDoCount').textContent = this.value.length + '/500';
    });

    document.getElementById('kienNghiForm').addEventListener('submit', function(e) {
        const lyDo = document.getElementById('lyDo').value.trim();
        if (lyDo.length < 10) {
            e.preventDefault();
            alert('⚠️ Lý do đề nghị phải có ít nhất 10 ký tự!');
            return false;
        }
        if (!confirm('Xác nhận gửi kiến nghị tăng ca đến trưởng phòng?')) {
            e.preventDefault();
            return false;
        }
    });
});
</script>
@endpush
@endsection