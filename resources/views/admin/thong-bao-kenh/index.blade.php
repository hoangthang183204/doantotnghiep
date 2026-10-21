@extends('layouts.admin')
@section('title', 'Thông báo đa kênh')
@section('content')
<div class="hr-page space-y-6 text-gray-900 dark:text-gray-100">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Thông báo đa kênh</h1>
    <p class="text-sm text-gray-500">Trong hệ thống luôn được ghi. Email, Zalo OA, SMS và push mobile gửi thêm khi bật kênh và đã có thông tin kết nối.</p>
    @if(session('success'))<div class="p-3 rounded-lg bg-green-50 text-green-700">{{ session('success') }}</div>@endif
    <form method="POST" action="{{ route('admin.thong-bao-kenh.update') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 grid md:grid-cols-2 gap-3 text-sm">
        @csrf
        <label class="flex gap-2 items-center"><input type="checkbox" name="email" value="1" @checked($kenh->email ?? true)> Email</label>
        <label class="flex gap-2 items-center"><input type="checkbox" name="push" value="1" @checked($kenh->push ?? true)> Push mobile (PWA)</label>
        <label class="flex gap-2 items-center"><input type="checkbox" name="zalo" value="1" @checked($kenh->zalo ?? false)> Zalo OA</label>
        <input name="zalo_access_token" value="{{ $kenh->zalo_access_token ?? '' }}" placeholder="Zalo access token" class="rounded-lg border-gray-300">
        <label class="flex gap-2 items-center"><input type="checkbox" name="sms" value="1" @checked($kenh->sms ?? false)> SMS</label>
        <input name="sms_api_url" value="{{ $kenh->sms_api_url ?? '' }}" placeholder="URL API SMS" class="rounded-lg border-gray-300">
        <input name="sms_api_key" value="{{ $kenh->sms_api_key ?? '' }}" placeholder="API key SMS" class="rounded-lg border-gray-300 md:col-span-2">
        <button class="w-fit px-4 py-2 rounded-lg bg-blue-600 text-white">Lưu kênh</button>
    </form>
    @foreach($maus as $mau)
    <form method="POST" action="{{ route('admin.thong-bao-kenh.mau', $mau->id) }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 space-y-2">
        @csrf @method('PUT')
        <div class="text-sm text-gray-500">{{ $mau->ten }} · <code>{{ $mau->ma }}</code></div>
        <input name="tieu_de" value="{{ $mau->tieu_de }}" class="w-full rounded-lg border-gray-300">
        <textarea name="noi_dung" rows="2" class="w-full rounded-lg border-gray-300">{{ $mau->noi_dung }}</textarea>
        <button class="text-sm px-3 py-1.5 rounded bg-blue-600 hover:bg-blue-700 text-white">Lưu mẫu</button>
    </form>
    @endforeach
    <form method="POST" action="{{ route('admin.thong-bao-kenh.gui-thu') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 flex flex-wrap gap-2 items-end">
        @csrf
        <label class="text-sm">Gửi thử tới
            <select name="nguoi_dung_id" class="block rounded-lg border-gray-300">
                @foreach($nhanViens as $nv)
                    <option value="{{ $nv->id }}">{{ trim(($nv->hoSo->ho ?? '').' '.($nv->hoSo->ten ?? '')) ?: $nv->ten_dang_nhap }}</option>
                @endforeach
            </select>
        </label>
        <label class="text-sm">Mẫu
            <select name="ma" class="block rounded-lg border-gray-300">
                @foreach($maus as $mau)<option value="{{ $mau->ma }}">{{ $mau->ten }}</option>@endforeach
            </select>
        </label>
        <button class="px-4 py-2 rounded-lg bg-emerald-600 text-white">Gửi thử</button>
    </form>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700/60"><tr><th class="px-3 py-2 text-left">Kênh</th><th class="px-3 py-2 text-left">Tiêu đề</th><th>Trạng thái</th><th class="px-3 py-2 text-left">Chi tiết</th></tr></thead>
            <tbody>
                @foreach($nhatKy as $log)
                <tr class="border-t"><td class="px-3 py-2">{{ $log->kenh }}</td><td class="px-3 py-2">{{ $log->tieu_de }}</td><td class="text-center">{{ $log->trang_thai }}</td><td class="px-3 py-2 text-gray-500">{{ $log->chi_tiet }}</td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
