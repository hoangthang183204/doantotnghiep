{{-- resources/views/admin/notifications/index.blade.php --}}

@extends('layouts.admin')

@section('title', 'Thông báo')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Thông báo</h2>
            <div class="flex space-x-2">
                @if($notifications->whereNull('read_at')->count() > 0)
                    <a href="{{ route('admin.notifications.mark-all-read') }}" 
                       class="px-3 py-1 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded transition-colors">
                        <i class="fas fa-check-double mr-1"></i> Đánh dấu đã đọc
                    </a>
                @endif
                <a href="{{ route('admin.dashboard') }}" 
                   class="px-3 py-1 text-sm bg-gray-600 hover:bg-gray-700 text-white rounded transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i> Quay lại
                </a>
            </div>
        </div>

        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($notifications as $notification)
                <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors {{ $notification->read_at ? '' : 'bg-blue-50 dark:bg-blue-900/20' }}">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center 
                                {{ ($notification->data['color'] ?? 'blue') === 'success' ? 'bg-green-100 dark:bg-green-900' : 'bg-blue-100 dark:bg-blue-900' }}">
                                <i class="fas fa-{{ $notification->data['icon'] ?? 'bell' }} 
                                    {{ ($notification->data['color'] ?? 'blue') === 'success' ? 'text-green-600 dark:text-green-400' : 'text-blue-600 dark:text-blue-400' }}">
                                </i>
                            </div>
                        </div>
                        <div class="ml-3 flex-1">
                            <div class="flex justify-between items-start">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $notification->data['title'] ?? 'Thông báo' }}
                                </p>
                                <span class="text-xs text-gray-400">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                                {{ $notification->data['message'] ?? '' }}
                            </p>
                            <div class="mt-2 flex space-x-2">
                                @if(isset($notification->data['url']))
                                    <a href="{{ $notification->data['url'] }}" 
                                       class="text-xs text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                        <i class="fas fa-eye mr-1"></i> Xem chi tiết
                                    </a>
                                @endif
                                @if(!$notification->read_at)
                                    <a href="{{ route('admin.notifications.mark-read', $notification->id) }}" 
                                       class="text-xs text-green-600 hover:text-green-700 dark:text-green-400">
                                        <i class="fas fa-check mr-1"></i> Đánh dấu đã đọc
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <p class="text-gray-500 dark:text-gray-400">Không có thông báo nào</p>
                </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection