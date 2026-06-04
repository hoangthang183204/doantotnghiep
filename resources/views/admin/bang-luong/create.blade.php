@extends('layouts.admin')

@section('title', 'Tính lương')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tính lương nhân viên</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Tính lương cho tháng {{ $thangTinh }}/{{ $namTinh }}</p>
    </div>
    
    @if($exists)
    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <span class="text-yellow-800">Bảng lương tháng {{ $thangTinh }}/{{ $namTinh }} đã được tính!</span>
        </div>
    </div>
    @endif
    
    <form action="{{ route('admin.bang-luong.tinh') }}" method="POST" id="tinhLuongForm">
        @csrf
        <input type="hidden" name="thang" value="{{ $thangTinh }}">
        <input type="hidden" name="nam" value="{{ $namTinh }}">
        
        <div class="card p-6">
            <div class="mb-6">
                <label class="flex items-center gap-2 mb-4">
                    <input type="checkbox" id="checkAll" class="w-4 h-4 rounded border-gray-300 text-blue-600">
                    <span class="font-medium text-gray-700 dark:text-gray-300">Chọn tất cả</span>
                </label>
                
                <div class="grid grid-cols-1 gap-3 max-h-96 overflow-y-auto">
                    @forelse($nhanViens as $nv)
                    <label class="flex items-center gap-3 p-3 border rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors cursor-pointer">
                        <input type="checkbox" name="nhan_vien_ids[]" value="{{ $nv->id }}" 
                               class="nhan-vien-checkbox w-4 h-4 rounded border-gray-300 text-blue-600">
                        <div class="flex-1">
                            <p class="font-medium text-gray-900 dark:text-white">{{ $nv->ho_so->ho ?? '' }} {{ $nv->ho_so->ten ?? $nv->ten_dang_nhap }}</p>
                            <p class="text-sm text-gray-500">{{ $nv->chuc_vu->ten ?? 'Chưa có chức vụ' }} - {{ $nv->email }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Hợp đồng: {{ $nv->hop_dongs->first()->so_hop_dong ?? 'Chưa có' }}</p>
                            <p class="text-sm font-semibold text-blue-600">{{ number_format($nv->hop_dongs->first()->luong_co_ban ?? 0) }} đ</p>
                        </div>
                    </label>
                    @empty
                    <div class="text-center py-8 text-gray-500">
                        Không có nhân viên nào chưa được tính lương
                    </div>
                    @endforelse
                </div>
            </div>
            
            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('admin.bang-luong.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50 transition-colors">
                    Hủy
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                    Tính lương
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    document.getElementById('checkAll')?.addEventListener('change', function(e) {
        document.querySelectorAll('.nhan-vien-checkbox').forEach(cb => cb.checked = e.target.checked);
    });
</script>
@endsection