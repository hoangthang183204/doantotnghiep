{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Card 1 -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Tổng nhân viên</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">150</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                <i class="fas fa-users text-blue-600 dark:text-blue-400 text-xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Card 2 -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Đi muộn hôm nay</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">5</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center">
                <i class="fas fa-clock text-yellow-600 dark:text-yellow-400 text-xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Card 3 - Chỉ Admin thấy -->
    @can('user.view')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Tài khoản mới</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">12</p>
            </div>
            <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                <i class="fas fa-user-plus text-green-600 dark:text-green-400 text-xl"></i>
            </div>
        </div>
    </div>
    @endcan
    
    <!-- Card 4 - Chỉ HR thấy -->
    @can('recruitment.candidate')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Ứng viên mới</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">8</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center">
                <i class="fas fa-file-signature text-purple-600 dark:text-purple-400 text-xl"></i>
            </div>
        </div>
    </div>
    @endcan
</div>
@endsection