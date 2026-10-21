@extends('layouts.admin')
@section('title', 'Xếp ca')
@section('content')
<div class="hr-page space-y-6 text-gray-900 dark:text-gray-100">
    <a href="{{ route('admin.ca-lam-viec.index') }}" class="text-sm text-blue-600">← Danh sách ca</a>
    <h1 class="text-2xl font-bold">Xếp ca {{ \Carbon\Carbon::parse($tuNgay)->format('d/m') }} – {{ \Carbon\Carbon::parse($denNgay)->format('d/m/Y') }}</h1>
    @if(session('success'))<div class="p-3 rounded-lg bg-green-50 text-green-700">{{ session('success') }}</div>@endif
    <form method="GET" class="flex gap-2">
        <input type="date" name="tu_ngay" value="{{ $tuNgay }}" class="rounded-lg border-gray-300">
        <input type="date" name="den_ngay" value="{{ $denNgay }}" class="rounded-lg border-gray-300">
        <button class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm">Xem</button>
    </form>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700/60"><tr><th class="px-3 py-2 text-left">Nhân viên</th><th>Ngày</th><th>Ca</th><th></th></tr></thead>
            <tbody>
                @foreach($nhanViens as $nv)
                <tr class="border-t">
                    <td class="px-3 py-2">{{ trim(($nv->hoSo->ho ?? '').' '.($nv->hoSo->ten ?? '')) ?: $nv->ten_dang_nhap }}</td>
                    <td colspan="3" class="px-3 py-2">
                        <form method="POST" action="{{ route('admin.ca-lam-viec.store-phan-ca') }}" class="flex flex-wrap gap-2 items-center">
                            @csrf
                            <input type="hidden" name="nguoi_dung_id" value="{{ $nv->id }}">
                            <input type="date" name="ngay" value="{{ $tuNgay }}" required class="rounded-lg border-gray-300">
                            <select name="ca_lam_viec_id" class="rounded-lg border-gray-300" required>
                                @foreach($cas as $ca)
                                    <option value="{{ $ca->id }}">{{ $ca->ten }} ({{ substr($ca->gio_bat_dau,0,5) }}–{{ substr($ca->gio_ket_thuc,0,5) }})</option>
                                @endforeach
                            </select>
                            <button class="px-3 py-1 rounded bg-blue-600 text-white">Xếp</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
