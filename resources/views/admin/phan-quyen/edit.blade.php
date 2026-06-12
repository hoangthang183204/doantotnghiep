@extends('layouts.admin')

@section('title', 'Phân quyền: ' . $role->ten_hien_thi)

@section('content')
<div class="space-y-6 bg-gray-50 dark:bg-[#0f172a] min-h-screen p-6">
    
    <div class="bg-white dark:bg-gray-800/80 backdrop-blur border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                    🔐 Phân quyền: {{ $role->ten_hien_thi }}
                </h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">
                    Chọn các quyền cho vai trò này
                </p>
            </div>
            <a href="{{ route('admin.phan-quyen.index') }}" 
               class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Quay lại
            </a>
        </div>
    </div>
    
    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg flex justify-between items-center">
            <span>✅ {{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-xl font-bold">&times;</button>
        </div>
    @endif
    
    @if($errors->any())
        <div class="bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg">
            {{ $errors->first() }}
        </div>
    @endif
    
    <form action="{{ route('admin.phan-quyen.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">
            <div class="p-6">
                @php
                    $currentPermissions = $role->quyens->pluck('id')->toArray();
                    
                    // Chuyển đổi tên nhóm sang tiếng Việt
                    $nhomTiengViet = [
                        'dashboard' => 'Bảng điều khiển',
                        'hoso' => 'Hồ sơ',
                        'user' => 'Người dùng',
                        'chucvu' => 'Chức vụ',
                        'department' => 'Phòng ban',
                        'branch' => 'Chi nhánh',
                        'role' => 'Vai trò',
                        'attendance' => 'Chấm công',
                        'salary' => 'Lương',
                        'contract' => 'Hợp đồng',
                        'recruitment' => 'Tuyển dụng',
                        'leave' => 'Nghỉ phép',
                        'leave_type' => 'Loại nghỉ phép',
                        'approval' => 'Duyệt đơn',
                        'regulation' => 'Quy định',
                        'setting' => 'Cài đặt',
                        'report' => 'Báo cáo',
                        'chat' => 'Tin nhắn',
                        'notification' => 'Thông báo',
                    ];
                @endphp
                
                @foreach($permissions as $nhom => $nhomPermissions)
                    @php
                        $nhomId = 'nhom-' . Str::slug($nhom);
                        $tenNhom = $nhomTiengViet[$nhom] ?? ucfirst($nhom);
                        $allChecked = true;
                        foreach($nhomPermissions as $perm) {
                            if (!in_array($perm->id, $currentPermissions)) {
                                $allChecked = false;
                                break;
                            }
                        }
                    @endphp
                    <div class="mb-8 pb-6 border-b border-gray-200 dark:border-gray-700 last:border-0">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center">
                                    <span class="text-white text-sm font-bold">{{ substr($tenNhom, 0, 2) }}</span>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                                    {{ $tenNhom }}
                                </h3>
                                <span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 dark:bg-gray-700 text-gray-500">
                                    {{ count($nhomPermissions) }} quyền
                                </span>
                            </div>
                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                <input type="checkbox" 
                                       class="select-all-group w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                       data-group="{{ $nhomId }}"
                                       {{ $allChecked ? 'checked' : '' }}>
                                <span class="text-sm text-gray-500 dark:text-gray-400 hover:text-blue-600 transition">
                                    🔘 Chọn tất cả
                                </span>
                            </label>
                        </div>
                        
                        <div id="{{ $nhomId }}" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
                            @foreach($nhomPermissions as $perm)
                                <label class="flex items-center gap-2 cursor-pointer p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition group">
                                    <input type="checkbox" 
                                           name="permissions[]" 
                                           value="{{ $perm->id }}"
                                           {{ in_array($perm->id, $currentPermissions) ? 'checked' : '' }}
                                           class="group-checkbox w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                           data-group="{{ $nhomId }}">
                                    <span class="text-sm text-gray-700 dark:text-gray-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">
                                        {{ $perm->ten_hien_thi }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
        <div class="sticky bottom-0 mt-6 p-4 bg-white dark:bg-gray-800/90 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg backdrop-blur">
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    <span id="totalSelected">0</span> / <span id="totalPermissions">0</span> quyền được chọn
                </div>
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-lg transition shadow-md hover:shadow-lg flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Lưu phân quyền
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cập nhật tổng số quyền đã chọn
    function updateTotalSelected() {
        const allCheckboxes = document.querySelectorAll('input[name="permissions[]"]');
        const totalPermissions = allCheckboxes.length;
        const selectedPermissions = document.querySelectorAll('input[name="permissions[]"]:checked').length;
        
        document.getElementById('totalSelected').textContent = selectedPermissions;
        document.getElementById('totalPermissions').textContent = totalPermissions;
    }
    
    // Xử lý "Chọn tất cả" cho từng nhóm
    document.querySelectorAll('.select-all-group').forEach(selectAll => {
        selectAll.addEventListener('change', function() {
            const groupId = this.getAttribute('data-group');
            const groupCheckboxes = document.querySelectorAll(`#${groupId} .group-checkbox`);
            groupCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateTotalSelected();
        });
    });
    
    // Xử lý khi checkbox trong nhóm thay đổi
    document.querySelectorAll('.group-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const groupId = this.getAttribute('data-group');
            const groupCheckboxes = document.querySelectorAll(`#${groupId} .group-checkbox`);
            const allChecked = Array.from(groupCheckboxes).every(cb => cb.checked);
            const selectAllBtn = document.querySelector(`.select-all-group[data-group="${groupId}"]`);
            if (selectAllBtn) {
                selectAllBtn.checked = allChecked;
            }
            updateTotalSelected();
        });
    });
    
    // Khởi tạo tổng số
    updateTotalSelected();
});
</script>
@endpush

@push('styles')
<style>
    .sticky {
        position: sticky;
        z-index: 10;
    }
    
    .group-checkbox:checked + span {
        color: #3b82f6;
    }
    
    /* Hiệu ứng hover cho card */
    .bg-white.dark\:bg-gray-800\/80 {
        transition: all 0.2s ease;
    }
</style>
@endpush
@endsection