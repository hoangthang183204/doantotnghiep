@extends('layouts.admin')

@section('title', 'Phân quyền hệ thống')

@section('content')
<div class="space-y-6 bg-gray-50 dark:bg-[#0f172a] min-h-screen p-6">
    
    <div class="bg-white dark:bg-gray-800/80 rounded-xl shadow-sm p-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Phân quyền hệ thống</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Quản lý vai trò và phân quyền truy cập</p>
    </div>
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="bg-white dark:bg-gray-800/80 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr class="text-left text-sm text-gray-700 dark:text-gray-300">
                        <th class="px-6 py-3">ID</th>
                        <th class="px-6 py-3">Vai trò</th>
                        <th class="px-6 py-3">Mô tả</th>
                        <th class="px-6 py-3">Số quyền</th>
                        <th class="px-6 py-3 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                    <tr class="border-t border-gray-200 dark:border-gray-700">
                        <td class="px-6 py-4">{{ $role->id }}</td>
                        <td class="px-6 py-4 font-medium">{{ $role->ten_hien_thi }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $role->mo_ta ?? '--' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-sm">
                                {{ $role->quyens->count() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.phan-quyen.edit', $role->id) }}" 
                               class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm transition">
                                🛠️ Phân quyền
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection