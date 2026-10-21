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
            <thead class="bg-gray-50 text-left"><tr><th class="px-3 py-2">Thời điểm</th><th class="px-3 py-2">Người</th><th class="px-3 py-2">Hành động</th><th class="px-3 py-2">Chức năng</th><th class="px-3 py-2">IP</th></tr></thead>
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
                    <td class="px-3 py-2">{{ $log->ip }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-3 py-8 text-center text-gray-500">Chưa có thao tác ghi nhận.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $logs->links() }}
</div>
@endsection
