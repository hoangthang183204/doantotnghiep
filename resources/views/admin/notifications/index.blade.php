{{-- resources/views/admin/notifications/index.blade.php --}}

@extends('layouts.admin')

@section('title', 'Thông báo')

@section('content')
<div class="space-y-6">

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold text-gray-800 dark:text-white">
                    📬 Thông báo
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Tất cả thông báo của bạn
                </p>
            </div>
            <div class="flex gap-2">
                @if($notifications->whereNull('read_at')->count() > 0)
                    <a href="{{ route('admin.notifications.mark-all-read') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition text-sm">
                        ✅ Đánh dấu đã đọc
                    </a>
                @endif
                <a href="{{ route('admin.dashboard') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition text-sm">
                    ← Quay lại
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        @forelse($notifications as $notification)
            @php
                $data = $notification->data;
                $icon = $data['icon'] ?? 'bell';
                $color = $data['color'] ?? 'blue';
                $title = $data['title'] ?? 'Thông báo';
                $message = $data['message'] ?? '';
                $url = $data['url'] ?? '#';
            @endphp
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition 
                {{ $notification->read_at ? '' : 'bg-blue-50 dark:bg-blue-900/20' }}">
                
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 mt-1">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center
                            {{ $color === 'success' ? 'bg-green-100 dark:bg-green-900/30' : 
                               ($color === 'danger' ? 'bg-red-100 dark:bg-red-900/30' : 
                               ($color === 'warning' ? 'bg-yellow-100 dark:bg-yellow-900/30' : 
                               'bg-blue-100 dark:bg-blue-900/30')) }}">
                            <i class="fas fa-{{ $icon }} 
                                {{ $color === 'success' ? 'text-green-600 dark:text-green-400' : 
                                   ($color === 'danger' ? 'text-red-600 dark:text-red-400' : 
                                   ($color === 'warning' ? 'text-yellow-600 dark:text-yellow-400' : 
                                   'text-blue-600 dark:text-blue-400')) }}">
                            </i>
                        </div>
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $title }}
                            </p>
                            <span class="text-xs text-gray-400 whitespace-nowrap ml-2">
                                {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                            {{ $message }}
                        </p>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @if($url && $url != '#')
                                <a href="{{ $url }}" 
                                   class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 transition">
                                    <i class="fas fa-eye mr-1"></i> Xem chi tiết
                                </a>
                            @endif
                            @if(!$notification->read_at)
                                <a href="{{ route('admin.notifications.mark-read', $notification->id) }}" 
                                   class="text-xs text-green-600 hover:text-green-800 dark:text-green-400 transition">
                                    <i class="fas fa-check mr-1"></i> Đánh dấu đã đọc
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="py-16 text-center">
                <div class="text-6xl mb-4">🔔</div>
                <p class="text-gray-500 dark:text-gray-400 text-lg">Không có thông báo nào</p>
                <p class="text-sm text-gray-400 mt-1">Khi có thông báo mới, chúng sẽ hiển thị ở đây</p>
            </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection