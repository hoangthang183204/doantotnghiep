@extends('layouts.admin')
@section('title', 'Nhật ký thao tác')
@section('content')
<div class="hr-page space-y-6 text-gray-900 dark:text-gray-100">
    <div class="flex justify-between gap-3 flex-wrap">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nhật ký thao tác</h1>
            <p class="text-sm text-gray-500">Ai sửa dữ liệu, duyệt đơn, tính lương — và vào lúc nào.</p>
        </div>
        <form method="GET"><input name="q" value="{{ request('q') }}" placeholder="Tìm route, đường dẫn" class="rounded-lg border-gray-300"><button class="ml-2 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm">Lọc</button></form>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left"><tr><th class="px-3 py-2">Thời điểm</th><th class="px-3 py-2">Người</th><th class="px-3 py-2">Hành động</th><th class="px-3 py-2">Chức năng</th><th class="px-3 py-2">Giá trị cũ → mới</th><th class="px-3 py-2">IP</th></tr></thead>
            <tbody>
                @forelse($logs as $log)
                <tr class="border-t align-top">
                    <td class="px-3 py-2 whitespace-nowrap">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                    <td class="px-3 py-2">{{ trim(($log->nguoiDung->hoSo->ho ?? '').' '.($log->nguoiDung->hoSo->ten ?? '')) ?: ($log->nguoiDung->ten_dang_nhap ?? '—') }}</td>
                    <td class="px-3 py-2">{{ $log->hanh_dong }}</td>
                    <td class="px-3 py-2">
                        <div>{{ $log->route }}</div>
                        <div class="text-xs text-gray-500">{{ $log->mo_ta }}</div>
                    </td>
                    <td class="px-3 py-2 text-xs">
                        @if(is_array($log->du_lieu) && isset($log->du_lieu['cu']))
                            @foreach($log->du_lieu['moi'] ?? [] as $cot => $moi)
                                <div><b>{{ $cot }}</b>: {{ $log->du_lieu['cu'][$cot] ?? '—' }} → {{ $moi }}</div>
                            @endforeach
                        @else
                            —
                        @endif
                    </td>
                    <td class="px-3 py-2">{{ $log->ip }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-3 py-8 text-center text-gray-500">Chưa có thao tác ghi nhận.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="flex flex-wrap items-center justify-between gap-3 text-sm">
        <p class="text-gray-500">Hiển thị {{ $logs->firstItem() ?? 0 }}–{{ $logs->lastItem() ?? 0 }} / {{ $logs->total() }} bản ghi</p>
        @if ($logs->lastPage() > 1)
            <div class="flex flex-wrap gap-1">
                @if ($logs->onFirstPage())
                    <span class="px-3 py-1 rounded-lg border border-gray-200 text-gray-400">Trước</span>
                @else
                    <a href="{{ $logs->previousPageUrl() }}" class="px-3 py-1 rounded-lg border border-gray-200 bg-white dark:bg-gray-800">Trước</a>
                @endif
                @foreach ($logs->getUrlRange(1, $logs->lastPage()) as $trang => $url)
                    <a href="{{ $url }}" class="px-3 py-1 rounded-lg border {{ $trang == $logs->currentPage() ? 'bg-blue-600 text-white border-blue-600' : 'border-gray-200 bg-white dark:bg-gray-800' }}">{{ $trang }}</a>
                @endforeach
                @if ($logs->hasMorePages())
                    <a href="{{ $logs->nextPageUrl() }}" class="px-3 py-1 rounded-lg border border-gray-200 bg-white dark:bg-gray-800">Sau</a>
                @else
                    <span class="px-3 py-1 rounded-lg border border-gray-200 text-gray-400">Sau</span>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
