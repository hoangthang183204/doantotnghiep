@extends('layouts.admin')

@section('title', 'Theo dõi kết quả đào tạo')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">
                        Theo dõi kết quả đào tạo
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                        Cập nhật kết quả sau khi nhân viên hoàn thành khóa đào tạo
                    </p>
                </div>
            </div>
            <a href="{{ route('admin.dao-tao.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm transition gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Quay lại
            </a>
        </div>
    </div>

    @include('layouts.partials.alerts')

    <form action="{{ route('admin.dao-tao.update', $daoTao->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">

            {{-- Thông tin cố định --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm border-b border-gray-200 dark:border-gray-700 pb-6 mb-6">
                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Mã nhân viên</div>
                    <div class="font-medium text-gray-800 dark:text-white">{{ $daoTao->hoSo->ma_nhan_vien }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Nhân viên</div>
                    <div class="font-medium text-gray-800 dark:text-white">{{ $daoTao->hoSo->ho_ten }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Khóa học</div>
                    <div class="font-medium text-gray-800 dark:text-white">{{ $daoTao->ten_khoa_hoc }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Đơn vị đào tạo</div>
                    <div class="font-medium text-gray-800 dark:text-white">{{ $daoTao->to_chuc ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Ngày bắt đầu</div>
                    <div class="font-medium text-gray-800 dark:text-white">{{ optional($daoTao->ngay_bat_dau)->format('d/m/Y') }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Chi phí</div>
                    <div class="font-medium text-blue-600">{{ number_format($daoTao->chi_phi) }} VNĐ</div>
                </div>
            </div>

            {{-- Form cập nhật --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Ngày kết thúc --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Ngày kết thúc
                    </label>
                    <input type="date" name="ngay_ket_thuc" value="{{ old('ngay_ket_thuc', optional($daoTao->ngay_ket_thuc)->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:[color-scheme:dark]">
                </div>

                {{-- Kết quả --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Kết quả đào tạo
                    </label>
                    <select name="ket_qua" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Chọn kết quả --</option>
                        <option value="Xuất sắc" {{ old('ket_qua', $daoTao->ket_qua)=='Xuất sắc'?'selected':'' }}>Xuất sắc</option>
                        <option value="Đạt" {{ old('ket_qua', $daoTao->ket_qua)=='Đạt'?'selected':'' }}>Đạt</option>
                        <option value="Không đạt" {{ old('ket_qua', $daoTao->ket_qua)=='Không đạt'?'selected':'' }}>Không đạt</option>
                        <option value="Đang học" {{ old('ket_qua', $daoTao->ket_qua)=='Đang học'?'selected':'' }}>Đang học</option>
                    </select>
                </div>

                {{-- Chứng chỉ --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Chứng chỉ
                    </label>
                    <div class="flex gap-6 pt-1">
                        <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700 dark:text-gray-300">
                            <input type="radio" name="co_chung_chi" value="1" {{ old('co_chung_chi', $daoTao->co_chung_chi)==1?'checked':'' }}>
                            Đã cấp
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700 dark:text-gray-300">
                            <input type="radio" name="co_chung_chi" value="0" {{ old('co_chung_chi', $daoTao->co_chung_chi)==0?'checked':'' }}>
                            Chưa cấp
                        </label>
                    </div>
                </div>

            </div>

            {{-- Đánh giá --}}
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    Đánh giá sau đào tạo
                </label>
                <textarea name="ghi_chu" rows="4" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('ghi_chu', $daoTao->ghi_chu) }}</textarea>
            </div>

        </div>

        {{-- FOOTER --}}
        <div class="flex justify-end gap-3 mt-6">
            <a href="{{ route('admin.dao-tao.index') }}" class="px-5 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium transition">
                Hủy
            </a>
            <button type="submit" class="px-6 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition shadow-sm">
                Lưu kết quả
            </button>
        </div>

    </form>

</div>
@endsection