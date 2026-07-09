@extends('layouts.admin')

@section('title', 'Phân quyền: ' . $role->ten_hien_thi)

@section('content')
<div class="space-y-6 p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/25">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Phân quyền: <span class="text-indigo-600 dark:text-indigo-400">{{ $role->ten_hien_thi }}</span>
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                        Chọn các quyền cho vai trò này
                    </p>
                </div>
            </div>
        </div>
        <a href="{{ route('admin.phan-quyen.index') }}"
            class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-xl text-sm font-medium transition-all duration-200 gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Quay lại
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-gray-800/80 rounded-xl p-4 border border-gray-200/50 dark:border-gray-700/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Đã chọn</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white" id="totalSelected">0</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800/80 rounded-xl p-4 border border-gray-200/50 dark:border-gray-700/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tổng quyền</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white" id="totalPermissions">0</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800/80 rounded-xl p-4 border border-gray-200/50 dark:border-gray-700/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tiến độ</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white" id="progressPercent">0%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Actions -->
    <div class="bg-white dark:bg-gray-800/80 border border-gray-200/50 dark:border-gray-700/50 rounded-xl shadow-sm p-4">
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[200px] relative">
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="searchPermission" placeholder="Tìm kiếm quyền..."
                    class="w-full pl-9 pr-4 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
            </div>
            <div class="flex flex-wrap gap-2">
                <button onclick="selectAll()"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Chọn tất cả
                </button>
                <button onclick="deselectAll()"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Bỏ chọn
                </button>
                <button onclick="resetFilter()"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Reset
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/20 border-l-4 border-emerald-500 text-emerald-700 dark:text-emerald-400 px-4 py-3 rounded-lg flex justify-between items-center">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-xl font-bold hover:text-emerald-800 dark:hover:text-emerald-300 transition">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <form action="{{ route('admin.phan-quyen.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-4">
            @php
                $currentPermissions = $role->quyens->pluck('id')->toArray();

                // ============================================================
                // NHÓM QUYỀN - ĐÃ CẬP NHẬT ĐẦY ĐỦ
                // ============================================================
                $nhomTiengViet = [
                    // ⭐ Dashboard có 2 loại: Employee (mặc định) và Admin (phân quyền)
                    'dashboard' => '📊 Dashboard',
                    'hoso' => '👤 Hồ sơ nhân viên',
                    'user' => '👥 Người dùng',
                    'department' => '🏢 Phòng ban',
                    'chucvu' => '💼 Chức vụ',
                    'attendance' => '⏰ Chấm công',
                    'overtime' => '🕐 Tăng ca',
                    'adjustment' => '✏️ Chỉnh công',
                    'salary' => '💰 Lương',
                    'payroll' => '📄 Bảng lương cá nhân',
                    'allowance' => '🎁 Phụ cấp',
                    'contract' => '📝 Hợp đồng',
                    'training' => '🎓 Đào tạo',
                    'chung-chi' => '📜 Chứng chỉ',
                    'role' => '🔑 Vai trò',
                    'permission' => '🔐 Phân quyền',
                    'leave_type' => '📋 Loại nghỉ phép',
                    'leave' => '🏖️ Nghỉ phép',
                    'setting' => '⚙️ Cài đặt',
                    'time' => '⏱️ Quản lý thời gian',
                    'approval' => '✅ Duyệt đơn',
                    'notification' => '🔔 Thông báo',
                    'profile' => '👤 Hồ sơ cá nhân',
                    'regulation' => '📜 Quy định',
                ];

                // Màu sắc cho từng nhóm
                $groupColors = [
                    'dashboard' => 'from-indigo-500 to-indigo-600',
                    'hoso' => 'from-blue-500 to-blue-600',
                    'user' => 'from-cyan-500 to-cyan-600',
                    'department' => 'from-emerald-500 to-emerald-600',
                    'chucvu' => 'from-teal-500 to-teal-600',
                    'attendance' => 'from-orange-500 to-orange-600',
                    'overtime' => 'from-amber-500 to-amber-600',
                    'adjustment' => 'from-yellow-500 to-yellow-600',
                    'salary' => 'from-rose-500 to-rose-600',
                    'payroll' => 'from-fuchsia-500 to-fuchsia-600',
                    'allowance' => 'from-pink-500 to-pink-600',
                    'contract' => 'from-sky-500 to-sky-600',
                    'training' => 'from-cyan-500 to-sky-600',
                    'chung-chi' => 'from-cyan-500 to-blue-600',
                    'role' => 'from-violet-500 to-violet-600',
                    'permission' => 'from-purple-500 to-purple-600',
                    'leave_type' => 'from-teal-500 to-cyan-600',
                    'leave' => 'from-emerald-500 to-teal-600',
                    'setting' => 'from-gray-600 to-gray-700',
                    'time' => 'from-indigo-600 to-purple-600',
                    'approval' => 'from-green-500 to-emerald-600',
                    'notification' => 'from-rose-500 to-pink-500',
                    'profile' => 'from-blue-500 to-indigo-500',
                    'regulation' => 'from-blue-600 to-indigo-600',
                ];
            @endphp

            @foreach($permissions as $nhom => $nhomPermissions)
                @php
                    $nhomId = 'nhom-' . Str::slug($nhom);
                    $tenNhom = $nhomTiengViet[$nhom] ?? ucfirst($nhom);
                    $color = $groupColors[$nhom] ?? 'from-indigo-500 to-indigo-600';
                    $allChecked = true;
                    foreach ($nhomPermissions as $perm) {
                        if (!in_array($perm->id, $currentPermissions)) {
                            $allChecked = false;
                            break;
                        }
                    }
                    
                    // ⭐ Kiểm tra xem nhóm dashboard có quyền admin không
                    $hasDashboardAdmin = false;
                    if ($nhom == 'dashboard') {
                        foreach ($nhomPermissions as $perm) {
                            if ($perm->name == 'dashboard.admin' && in_array($perm->id, $currentPermissions)) {
                                $hasDashboardAdmin = true;
                                break;
                            }
                        }
                    }
                @endphp
                <div class="bg-white dark:bg-gray-800/80 rounded-xl border border-gray-200/50 dark:border-gray-700/50 shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden">
                    <div class="px-5 py-3 bg-gray-50/50 dark:bg-gray-700/30 border-b border-gray-200/50 dark:border-gray-700/50 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br {{ $color }} flex items-center justify-center shadow-sm">
                                <span class="text-white text-xs font-bold">{{ substr($tenNhom, 0, 2) }}</span>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-800 dark:text-white">
                                {{ $tenNhom }}
                            </h3>
                            <span class="px-2 py-0.5 text-xs rounded-full bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300">
                                {{ count($nhomPermissions) }}
                            </span>
                            {{-- ⭐ BADGE cho dashboard admin --}}
                            @if($nhom == 'dashboard')
                                @if($hasDashboardAdmin)
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                        👑 Tổng quan
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                                        📊 Thống kê cá nhân
                                    </span>
                                @endif
                            @endif
                        </div>
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox"
                                class="select-all-group w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0"
                                data-group="{{ $nhomId }}" {{ $allChecked ? 'checked' : '' }}>
                            <span class="text-xs text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                                Chọn tất cả
                            </span>
                        </label>
                    </div>

                    <div id="{{ $nhomId }}" class="p-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-2">
                        @foreach($nhomPermissions as $perm)
                            @php
                                // ⭐ Đánh dấu quyền dashboard.admin là đặc biệt
                                $isSpecial = ($perm->name == 'dashboard.admin');
                            @endphp
                            <label class="flex items-center gap-2 cursor-pointer px-3 py-2 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-900/10 transition-all duration-150 group {{ $isSpecial ? 'border border-indigo-200 dark:border-indigo-800 bg-indigo-50/50 dark:bg-indigo-900/10' : '' }}">
                                <input type="checkbox" name="permissions[]" value="{{ $perm->id }}"
                                    {{ in_array($perm->id, $currentPermissions) ? 'checked' : '' }}
                                    class="group-checkbox w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0"
                                    data-group="{{ $nhomId }}">
                                <span class="text-sm text-gray-700 dark:text-gray-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors {{ $isSpecial ? 'font-semibold text-indigo-600 dark:text-indigo-400' : '' }}">
                                    {{ $perm->ten_hien_thi }}
                                    @if($isSpecial)
                                        <span class="text-xs text-indigo-400 dark:text-indigo-500 ml-1">⭐</span>
                                    @endif
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Footer -->
        <div class="sticky bottom-0 mt-6 p-4 bg-white/95 dark:bg-gray-800/95 backdrop-blur border border-gray-200/50 dark:border-gray-700/50 rounded-xl shadow-lg">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-6 text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-indigo-500"></div>
                        <span class="text-gray-600 dark:text-gray-300">
                            Đã chọn: <span id="totalSelected" class="font-semibold text-indigo-600 dark:text-indigo-400">0</span>
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-gray-300 dark:bg-gray-600"></div>
                        <span class="text-gray-600 dark:text-gray-300">
                            Tổng: <span id="totalPermissions" class="font-semibold">0</span>
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                        <span class="text-gray-600 dark:text-gray-300">
                            Tiến độ: <span id="progressPercent" class="font-semibold text-emerald-600 dark:text-emerald-400">0%</span>
                        </span>
                    </div>
                </div>
                <button type="submit"
                    class="w-full sm:w-auto px-8 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white rounded-xl text-sm font-medium transition-all duration-200 shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
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
    function updateTotalSelected() {
        const allCheckboxes = document.querySelectorAll('input[name="permissions[]"]');
        const totalPermissions = allCheckboxes.length;
        const selectedPermissions = document.querySelectorAll('input[name="permissions[]"]:checked').length;
        const percent = totalPermissions > 0 ? Math.round((selectedPermissions / totalPermissions) * 100) : 0;

        document.getElementById('totalSelected').textContent = selectedPermissions;
        document.getElementById('totalPermissions').textContent = totalPermissions;
        document.getElementById('progressPercent').textContent = percent + '%';
    }

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

    const searchInput = document.getElementById('searchPermission');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const keyword = this.value.toLowerCase().trim();
            const labels = document.querySelectorAll('.group-checkbox + span');
            labels.forEach(label => {
                const parent = label.closest('label');
                const text = label.textContent.toLowerCase();
                parent.style.display = (keyword === '' || text.includes(keyword)) ? 'flex' : 'none';
            });
        });
    }

    updateTotalSelected();
});

window.selectAll = function() {
    document.querySelectorAll('input[name="permissions[]"]').forEach(cb => {
        cb.checked = true;
        cb.dispatchEvent(new Event('change'));
    });
    document.querySelectorAll('.select-all-group').forEach(btn => btn.checked = true);
};

window.deselectAll = function() {
    document.querySelectorAll('input[name="permissions[]"]').forEach(cb => {
        cb.checked = false;
        cb.dispatchEvent(new Event('change'));
    });
    document.querySelectorAll('.select-all-group').forEach(btn => btn.checked = false);
};

window.resetFilter = function() {
    const searchInput = document.getElementById('searchPermission');
    if (searchInput) {
        searchInput.value = '';
        document.querySelectorAll('.group-checkbox + span').forEach(label => {
            label.closest('label').style.display = 'flex';
        });
    }
};
</script>
@endpush

@push('styles')
<style>
    .sticky {
        position: sticky;
        z-index: 10;
        bottom: 1rem;
    }
    .group-checkbox {
        accent-color: #6366f1;
        cursor: pointer;
        flex-shrink: 0;
    }
    .group-checkbox:checked + span {
        color: #6366f1;
        font-weight: 500;
    }
    .group:hover {
        background: rgba(99, 102, 241, 0.05);
    }
</style>
@endpush
@endsection