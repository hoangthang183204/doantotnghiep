@extends('layouts.admin')

@section('title', 'Phân quyền: ' . $role->ten_hien_thi)

@section('content')
    <div class="space-y-6 p-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <div
                        class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/25">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Quay lại
            </a>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-800/80 rounded-xl p-4 border border-gray-200/50 dark:border-gray-700/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
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
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
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
                    <div
                        class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tiến độ</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white" id="progressPercent">0%</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800/80 rounded-xl p-4 border border-gray-200/50 dark:border-gray-700/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Vai trò</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $role->name }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Actions -->
        <div
            class="bg-white dark:bg-gray-800/80 border border-gray-200/50 dark:border-gray-700/50 rounded-xl shadow-sm p-4">
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex-1 min-w-[200px] relative">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" id="searchPermission" placeholder="Tìm kiếm quyền..."
                        class="w-full pl-9 pr-4 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                </div>

                <!-- Bộ lọc -->
                <select id="filterGroup"
                    class="px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                    <option value="all">📋 Tất cả nhóm</option>
                    <option value="admin">🔒 Quyền Admin</option>
                    <option value="employee">👤 Quyền Nhân viên</option>
                    <option value="system">⚡ Quyền hệ thống</option>
                </select>

                <select id="filterStatus"
                    class="px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                    <option value="all">🔄 Tất cả trạng thái</option>
                    <option value="checked">✅ Đã chọn</option>
                    <option value="unchecked">⬜ Chưa chọn</option>
                </select>

                <div class="flex flex-wrap gap-2">
                    <button onclick="toggleAllGroups()"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        Ẩn/Hiện
                    </button>
                    <button onclick="applySuggested()"
                        class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Gợi ý
                    </button>
                    <button onclick="selectAll()"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Chọn tất cả
                    </button>
                    <button onclick="deselectAll()"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Bỏ chọn
                    </button>
                    <button onclick="resetFilter()"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset
                    </button>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div
                class="bg-emerald-50 dark:bg-emerald-900/20 border-l-4 border-emerald-500 text-emerald-700 dark:text-emerald-400 px-4 py-3 rounded-lg flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()"
                    class="text-xl font-bold hover:text-emerald-800 dark:hover:text-emerald-300 transition">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div
                class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()"
                    class="text-xl font-bold hover:text-red-800 dark:hover:text-red-300 transition">&times;</button>
            </div>
        @endif

        @if ($errors->any())
            <div
                class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('admin.phan-quyen.update', $role->id) }}" method="POST" id="permissionForm">
            @csrf
            @method('PUT')

            <div class="space-y-4" id="permissionContainer">
                @php
                    $currentPermissions = $role->quyens->pluck('id')->toArray();

                    // Màu sắc cho từng nhóm - ĐÃ XÓA RECRUITMENT
                    $groupColors = [
                        'system' => 'from-red-600 to-red-700',
                        'dashboard' => 'from-blue-600 to-blue-700',
                        'employee' => 'from-emerald-600 to-emerald-700',
                        'profile' => 'from-green-600 to-teal-600',
                        'department' => 'from-cyan-600 to-cyan-700',
                        'position' => 'from-teal-600 to-teal-700',
                        'attendance' => 'from-orange-500 to-orange-600',
                        'overtime' => 'from-amber-500 to-amber-600',
                        'adjustment' => 'from-yellow-500 to-yellow-600',
                        'leave_type' => 'from-lime-500 to-lime-600',
                        'leave' => 'from-orange-600 to-amber-600',
                        'salary' => 'from-rose-600 to-rose-700',
                        'allowance' => 'from-pink-500 to-pink-600',
                        'salary_increase' => 'from-fuchsia-600 to-fuchsia-700',
                        'contract' => 'from-purple-600 to-purple-700',
                        'training' => 'from-indigo-500 to-indigo-600',
                        'certificate' => 'from-violet-500 to-violet-600',
                        'reward_discipline' => 'from-yellow-600 to-amber-600',
                        'notification' => 'from-gray-600 to-gray-700',
                        'personal' => 'from-blue-500 to-indigo-500',
                    ];

                    // Tên nhóm tiếng Việt - ĐÃ XÓA RECRUITMENT

                    $nhomTiengViet = [
                        'system' => '⚙️ Hệ thống',
                        'dashboard' => '📊 Dashboard',
                        'employee' => '👥 Nhân viên',
                        'profile' => '📋 Hồ sơ',
                        'department' => '🏢 Phòng ban',
                        'position' => '💼 Chức vụ',
                        'attendance' => '⏰ Chấm công',
                        'overtime' => '🕐 Tăng ca',
                        'adjustment' => '✏️ Chỉnh công',
                        'leave_type' => '📋 Loại nghỉ phép',
                        'leave' => '🏖️ Nghỉ phép',
                        'salary' => '💰 Lương',
                        'allowance' => '🎁 Phụ cấp',
                        'salary_increase' => '📈 Tăng lương',
                        'contract' => '📝 Hợp đồng',
                        'training' => '🎓 Đào tạo',
                        'certificate' => '📜 Chứng chỉ',
                        'role' => '🔑 Vai trò', // ✅ THÊM VÀO
                        'permission' => '🔐 Phân quyền',
                        'reward_discipline' => '🏅 Khen thưởng/Kỷ luật',
                        'notification' => '🔔 Thông báo',
                        'personal' => '👤 Cá nhân',
                        'regulation' => '📜 Quy định',
                        'report' => '📊 Báo cáo', // ✅ THÊM VÀO
                    ];

                @endphp

                @foreach ($permissions as $nhom => $nhomPermissions)
                    @php
                        $nhomId = 'nhom-' . Str::slug($nhom);
                        $tenNhom = $nhomTiengViet[$nhom] ?? ucfirst($nhom);
                        $color = $groupColors[$nhom] ?? 'from-indigo-500 to-indigo-600';

                        // Kiểm tra loại nhóm
                        $isSystem = in_array($nhom, ['system']);
                        $isAdmin =
                            in_array($nhom, ['department', 'salary', 'contract']) || Str::contains($nhom, 'admin');
                        $badgeType = $isSystem
                            ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'
                            : ($isAdmin
                                ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400'
                                : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400');
                        $badgeText = $isSystem ? '🔒 Hệ thống' : ($isAdmin ? '🔑 Admin' : '👤 Employee');

                        $allChecked = true;
                        foreach ($nhomPermissions as $perm) {
                            if (!in_array($perm->id, $currentPermissions)) {
                                $allChecked = false;
                                break;
                            }
                        }

                        $checkedCount = 0;
                        foreach ($nhomPermissions as $perm) {
                            if (in_array($perm->id, $currentPermissions)) {
                                $checkedCount++;
                            }
                        }
                        $percent =
                            count($nhomPermissions) > 0 ? round(($checkedCount / count($nhomPermissions)) * 100) : 0;
                    @endphp
                    <div
                        class="bg-white dark:bg-gray-800/80 rounded-xl border border-gray-200/50 dark:border-gray-700/50 shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden group-container">
                        <div
                            class="px-5 py-3 bg-gray-50/50 dark:bg-gray-700/30 border-b border-gray-200/50 dark:border-gray-700/50 flex items-center justify-between">
                            <div class="flex items-center gap-3 flex-wrap">
                                <div
                                    class="w-8 h-8 rounded-lg bg-gradient-to-br {{ $color }} flex items-center justify-center shadow-sm">
                                    <span class="text-white text-xs font-bold">{{ substr($tenNhom, 0, 2) }}</span>
                                </div>
                                <h3 class="text-sm font-semibold text-gray-800 dark:text-white">
                                    {{ $tenNhom }}
                                </h3>
                                <span class="px-2 py-0.5 text-xs rounded-full {{ $badgeType }}">
                                    {{ $badgeText }}
                                </span>
                                <span
                                    class="px-2 py-0.5 text-xs rounded-full bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300">
                                    {{ count($nhomPermissions) }}
                                </span>
                                @if ($isSystem)
                                    <span
                                        class="px-2 py-0.5 text-xs rounded-full bg-red-200 dark:bg-red-900/30 text-red-700 dark:text-red-400 animate-pulse">
                                        ⚠️ Hạn chế
                                    </span>
                                @endif
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="hidden sm:flex items-center gap-2">
                                    <div class="w-24 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r {{ $color }} transition-all duration-500"
                                            style="width: {{ $percent }}%" id="progress-{{ $nhomId }}">
                                        </div>
                                    </div>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 min-w-[40px] text-right">
                                        {{ $percent }}%
                                    </span>
                                </div>
                                <label class="flex items-center gap-2 cursor-pointer select-none">
                                    <input type="checkbox"
                                        class="select-all-group w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0"
                                        data-group="{{ $nhomId }}" {{ $allChecked ? 'checked' : '' }}
                                        {{ $isSystem ? 'disabled' : '' }}>
                                    <span
                                        class="text-xs text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                                        Chọn tất cả
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div id="{{ $nhomId }}"
                            class="p-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-2">
                            @foreach ($nhomPermissions as $perm)
                                @php
                                    $isSystemPerm = Str::startsWith($perm->name, 'system.');
                                    $isChecked = in_array($perm->id, $currentPermissions);
                                @endphp
                                <label
                                    class="flex items-center gap-2 cursor-pointer px-3 py-2 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-900/10 transition-all duration-150 group 
                                {{ $isChecked ? 'bg-indigo-50/50 dark:bg-indigo-900/20 border border-indigo-200/50 dark:border-indigo-800/50' : '' }}
                                {{ $isSystemPerm ? 'border border-red-200/50 dark:border-red-800/50' : '' }}">
                                    <input type="checkbox" name="permissions[]" value="{{ $perm->id }}"
                                        {{ $isChecked ? 'checked' : '' }}
                                        {{ $isSystemPerm && !Auth::user()->vaiTros()->where('name', 'Super Admin')->exists() ? 'disabled' : '' }}
                                        class="group-checkbox w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0
                                    {{ $isSystemPerm ? 'border-red-400' : '' }}"
                                        data-group="{{ $nhomId }}" data-permission="{{ $perm->name }}">
                                    <span
                                        class="text-sm text-gray-700 dark:text-gray-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors {{ $isChecked ? 'text-indigo-600 dark:text-indigo-400 font-medium' : '' }} {{ $isSystemPerm ? 'text-red-600 dark:text-red-400' : '' }}">
                                        {{ $perm->ten_hien_thi }}
                                        @if ($isSystemPerm)
                                            <span class="text-xs text-red-400 dark:text-red-500 ml-1">🔒</span>
                                        @endif
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Footer -->
            <div
                class="sticky bottom-0 mt-6 p-4 bg-white/95 dark:bg-gray-800/95 backdrop-blur border border-gray-200/50 dark:border-gray-700/50 rounded-xl shadow-lg">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex flex-wrap items-center gap-4 text-sm">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-indigo-500"></div>
                            <span class="text-gray-600 dark:text-gray-300">
                                Đã chọn: <span id="totalSelected"
                                    class="font-semibold text-indigo-600 dark:text-indigo-400">0</span>
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
                                Tiến độ: <span id="progressPercent"
                                    class="font-semibold text-emerald-600 dark:text-emerald-400">0%</span>
                            </span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-gray-400 dark:text-gray-500">
                            <span>🔒 = Quyền hệ thống</span>
                            <span class="hidden sm:inline">|</span>
                            <span>🔑 = Quyền Admin</span>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <button type="button" onclick="exportPermissions()"
                            class="px-3 py-1.5 text-xs bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition">
                            📤 Export
                        </button>
                        <button type="button" onclick="importPermissions()"
                            class="px-3 py-1.5 text-xs bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition">
                            📥 Import
                        </button>
                        <button type="submit"
                            class="w-full sm:w-auto px-8 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white rounded-xl text-sm font-medium transition-all duration-200 shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Lưu phân quyền
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // ============================================
                // 1. CẬP NHẬT THỐNG KÊ
                // ============================================
                function updateStats() {
                    const allCheckboxes = document.querySelectorAll('input[name="permissions[]"]:not([disabled])');
                    const totalPermissions = allCheckboxes.length;
                    const selectedPermissions = document.querySelectorAll(
                        'input[name="permissions[]"]:checked:not([disabled])').length;
                    const percent = totalPermissions > 0 ? Math.round((selectedPermissions / totalPermissions) * 100) :
                        0;

                    document.getElementById('totalSelected').textContent = selectedPermissions;
                    document.getElementById('totalPermissions').textContent = totalPermissions;
                    document.getElementById('progressPercent').textContent = percent + '%';

                    // Cập nhật tiến độ cho từng nhóm
                    document.querySelectorAll('.select-all-group').forEach(selectAll => {
                        const groupId = selectAll.getAttribute('data-group');
                        const groupCheckboxes = document.querySelectorAll(
                            `#${groupId} .group-checkbox:not([disabled])`);
                        const checked = document.querySelectorAll(
                            `#${groupId} .group-checkbox:checked:not([disabled])`).length;
                        const total = groupCheckboxes.length;
                        const pct = total > 0 ? Math.round((checked / total) * 100) : 0;

                        const progressBar = document.querySelector(`#progress-${groupId}`);
                        if (progressBar) {
                            progressBar.style.width = pct + '%';
                        }
                    });
                }

                // ============================================
                // 2. FILTER THEO NHÓM
                // ============================================
                document.getElementById('filterGroup').addEventListener('change', function() {
                    const value = this.value;
                    const containers = document.querySelectorAll('.group-container');

                    containers.forEach(container => {
                        const isSystem = container.querySelector('.bg-red-100') !== null;
                        const isAdmin = container.querySelector('.bg-purple-100') !== null;

                        let show = true;
                        if (value === 'admin') {
                            show = isAdmin && !isSystem;
                        } else if (value === 'employee') {
                            show = !isAdmin && !isSystem;
                        } else if (value === 'system') {
                            show = isSystem;
                        }

                        container.style.display = show ? '' : 'none';
                    });
                });

                // ============================================
                // 3. FILTER THEO TRẠNG THÁI
                // ============================================
                document.getElementById('filterStatus').addEventListener('change', function() {
                    const value = this.value;
                    const labels = document.querySelectorAll('.group-checkbox:not([disabled])');

                    labels.forEach(checkbox => {
                        const label = checkbox.closest('label');
                        if (value === 'all') {
                            label.style.display = '';
                        } else if (value === 'checked') {
                            label.style.display = checkbox.checked ? '' : 'none';
                        } else if (value === 'unchecked') {
                            label.style.display = !checkbox.checked ? '' : 'none';
                        }
                    });
                });

                // ============================================
                // 4. CHỌN/BỎ CHỌN TẤT CẢ
                // ============================================
                document.querySelectorAll('.select-all-group').forEach(selectAll => {
                    selectAll.addEventListener('change', function() {
                        if (this.disabled) return;
                        const groupId = this.getAttribute('data-group');
                        const groupCheckboxes = document.querySelectorAll(
                            `#${groupId} .group-checkbox:not([disabled])`);
                        groupCheckboxes.forEach(checkbox => {
                            checkbox.checked = this.checked;
                        });
                        updateStats();
                    });
                });

                document.querySelectorAll('.group-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        if (this.disabled) return;
                        const groupId = this.getAttribute('data-group');
                        const groupCheckboxes = document.querySelectorAll(
                            `#${groupId} .group-checkbox:not([disabled])`);
                        const allChecked = Array.from(groupCheckboxes).every(cb => cb.checked);
                        const selectAllBtn = document.querySelector(
                            `.select-all-group[data-group="${groupId}"]`);
                        if (selectAllBtn && !selectAllBtn.disabled) {
                            selectAllBtn.checked = allChecked;
                        }
                        updateStats();
                    });
                });

                // ============================================
                // 5. TÌM KIẾM
                // ============================================
                const searchInput = document.getElementById('searchPermission');
                if (searchInput) {
                    searchInput.addEventListener('input', function() {
                        const keyword = this.value.toLowerCase().trim();
                        const labels = document.querySelectorAll('.group-checkbox + span');
                        labels.forEach(label => {
                            const parent = label.closest('label');
                            const text = label.textContent.toLowerCase();
                            parent.style.display = (keyword === '' || text.includes(keyword)) ? 'flex' :
                                'none';
                        });
                    });
                }

                // ============================================
                // 6. TOGGLE ALL GROUPS
                // ============================================
                window.toggleAllGroups = function() {
                    const containers = document.querySelectorAll('.group-container');
                    const firstDisplay = containers[0]?.style.display;
                    const allHidden = firstDisplay === 'none';

                    containers.forEach(container => {
                        container.style.display = allHidden ? '' : 'none';
                    });
                };

                // ============================================
                // 7. APPLY SUGGESTED PERMISSIONS
                // ============================================
                window.applySuggested = function() {
                    @if (!empty($suggested))
                        const suggestedIds = @json($suggested);
                        document.querySelectorAll('input[name="permissions[]"]:not([disabled])').forEach(cb => {
                            cb.checked = suggestedIds.includes(parseInt(cb.value));
                            cb.dispatchEvent(new Event('change'));
                        });
                        showToast('✅ Đã áp dụng gợi ý quyền cho vai trò');
                    @else
                        showToast('ℹ️ Không có gợi ý cho vai trò này');
                    @endif
                };

                // ============================================
                // 8. EXPORT/IMPORT PERMISSIONS
                // ============================================
                window.exportPermissions = function() {
                    const selected = document.querySelectorAll(
                        'input[name="permissions[]"]:checked:not([disabled])');
                    const data = Array.from(selected).map(cb => ({
                        id: cb.value,
                        name: cb.getAttribute('data-permission') || cb.value
                    }));

                    const text = data.map(item => item.name).join('\n');
                    navigator.clipboard.writeText(text).then(() => {
                        showToast('✅ Đã sao chép ' + data.length + ' quyền');
                    }).catch(() => {
                        prompt('📋 Danh sách quyền đã chọn (copy và lưu lại):', text);
                    });
                };

                window.importPermissions = function() {
                    const text = prompt('📋 Dán danh sách quyền (mỗi quyền 1 dòng):');
                    if (!text) return;

                    const names = text.split('\n').map(s => s.trim()).filter(s => s);
                    const checkboxes = document.querySelectorAll('input[name="permissions[]"]:not([disabled])');
                    let imported = 0;

                    checkboxes.forEach(cb => {
                        const permName = cb.getAttribute('data-permission') || cb.value;
                        if (names.some(name => permName.includes(name) || name.includes(permName))) {
                            cb.checked = true;
                            cb.dispatchEvent(new Event('change'));
                            imported++;
                        }
                    });

                    showToast('✅ Đã import ' + imported + ' quyền');
                };

                // ============================================
                // 9. TOAST NOTIFICATION
                // ============================================
                function showToast(message) {
                    const toast = document.createElement('div');
                    toast.className =
                        'fixed bottom-4 right-4 bg-gray-800 text-white px-6 py-3 rounded-xl shadow-lg z-50 animate-slide-up';
                    toast.textContent = message;
                    document.body.appendChild(toast);
                    setTimeout(() => {
                        toast.classList.add('opacity-0', 'transition-opacity', 'duration-300');
                        setTimeout(() => toast.remove(), 300);
                    }, 3000);
                }

                // ============================================
                // 10. GLOBAL FUNCTIONS
                // ============================================
                window.selectAll = function() {
                    document.querySelectorAll('input[name="permissions[]"]:not([disabled])').forEach(cb => {
                        cb.checked = true;
                        cb.dispatchEvent(new Event('change'));
                    });
                    document.querySelectorAll('.select-all-group:not([disabled])').forEach(btn => btn.checked =
                        true);
                    updateStats();
                };

                window.deselectAll = function() {
                    document.querySelectorAll('input[name="permissions[]"]:not([disabled])').forEach(cb => {
                        cb.checked = false;
                        cb.dispatchEvent(new Event('change'));
                    });
                    document.querySelectorAll('.select-all-group:not([disabled])').forEach(btn => btn.checked =
                        false);
                    updateStats();
                };

                window.resetFilter = function() {
                    document.getElementById('searchPermission').value = '';
                    document.getElementById('filterGroup').value = 'all';
                    document.getElementById('filterStatus').value = 'all';

                    document.querySelectorAll('.group-container').forEach(container => {
                        container.style.display = '';
                    });

                    document.querySelectorAll('.group-checkbox').forEach(cb => {
                        cb.closest('label').style.display = '';
                    });

                    document.getElementById('filterGroup').dispatchEvent(new Event('change'));
                };

                // ============================================
                // 11. KEYBOARD SHORTCUTS
                // ============================================
                document.addEventListener('keydown', function(e) {
                    if (e.ctrlKey && e.key === 'a') {
                        e.preventDefault();
                        selectAll();
                    }
                    if (e.ctrlKey && e.key === 'd') {
                        e.preventDefault();
                        deselectAll();
                    }
                    if (e.ctrlKey && e.key === 'f') {
                        e.preventDefault();
                        document.getElementById('searchPermission').focus();
                    }
                    if (e.ctrlKey && e.key === 's') {
                        e.preventDefault();
                        document.getElementById('permissionForm').submit();
                    }
                });

                // ============================================
                // 12. KHỞI TẠO
                // ============================================
                updateStats();

                setTimeout(() => {
                    document.querySelectorAll('.bg-emerald-50, .bg-red-50').forEach(el => {
                        el.style.transition = 'opacity 0.5s';
                        el.style.opacity = '0';
                        setTimeout(() => el.remove(), 500);
                    });
                }, 5000);
            });

            // ============================================
            // 13. THEME SUPPORT - DARK MODE
            // ============================================
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.documentElement.classList.add('dark');
            }
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

            .group-checkbox:checked+span {
                color: #6366f1;
                font-weight: 500;
            }

            .group-checkbox:disabled {
                opacity: 0.5;
                cursor: not-allowed;
            }

            .group-checkbox:disabled+span {
                opacity: 0.7;
                cursor: not-allowed;
            }

            .group:hover {
                background: rgba(99, 102, 241, 0.05);
            }

            @keyframes slide-up {
                from {
                    opacity: 0;
                    transform: translateY(20px) scale(0.95);
                }

                to {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }
            }

            .animate-slide-up {
                animation: slide-up 0.3s ease-out;
            }

            .bg-red-100:hover,
            .bg-purple-100:hover,
            .bg-blue-100:hover {
                transform: scale(1.05);
                transition: transform 0.2s;
            }

            html {
                scroll-behavior: smooth;
            }

            ::-webkit-scrollbar {
                width: 6px;
                height: 6px;
            }

            ::-webkit-scrollbar-track {
                background: transparent;
            }

            ::-webkit-scrollbar-thumb {
                background: #6366f1;
                border-radius: 3px;
            }

            ::-webkit-scrollbar-thumb:hover {
                background: #4f46e5;
            }

            .dark ::-webkit-scrollbar-thumb {
                background: #818cf8;
            }

            .dark ::-webkit-scrollbar-thumb:hover {
                background: #6366f1;
            }
        </style>
    @endpush
@endsection
