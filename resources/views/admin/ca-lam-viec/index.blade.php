@extends('layouts.admin')
@section('title', 'Ca làm việc')
@section('content')
<div class="hr-page space-y-6 text-gray-900 dark:text-gray-100">
    <div class="flex flex-wrap justify-between items-start gap-3">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Ca làm việc</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Ba bước: khai báo các loại ca ở đây, xếp từng người theo ngày ở nút Xếp ca, rồi duyệt yêu cầu đổi ca bên dưới.</p>
        </div>
        <a href="{{ route('admin.ca-lam-viec.phan-ca') }}" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm">Xếp ca</a>
    </div>
    @if(session('success'))<div class="p-3 rounded-lg bg-green-50 text-green-700">{{ session('success') }}</div>@endif
    <form method="POST" action="{{ route('admin.ca-lam-viec.store') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 grid md:grid-cols-6 gap-3 items-end">
        @csrf
        <label>Tên ca<input name="ten" placeholder="Ví dụ: Sáng" required></label>
        <label>Mã<input name="ma" placeholder="SANG" required></label>
        <label>Loại
            <select name="loai">
                <option value="sang">Sáng</option><option value="chieu">Chiều</option><option value="toi">Tối</option>
                <option value="gay">Ca gãy</option><option value="hanh_chinh">Hành chính</option>
            </select>
        </label>
        <label>Giờ vào<input type="time" name="gio_bat_dau" required></label>
        <label>Giờ ra<input type="time" name="gio_ket_thuc" required></label>
        <label>Số giờ<input type="number" step="0.5" name="so_gio_lam_viec" value="8" required></label>
        <button class="md:col-span-6 w-fit px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white">Thêm ca</button>
    </form>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700/60"><tr><th class="px-3 py-2 text-left">Ca</th><th>Loại</th><th>Giờ</th><th>Số giờ</th></tr></thead>
            <tbody>
                @php $tenLoai = ['sang' => 'Sáng', 'chieu' => 'Chiều', 'toi' => 'Tối', 'gay' => 'Ca gãy', 'hanh_chinh' => 'Hành chính']; @endphp
                @foreach($cas as $ca)
                <tr class="border-t"><td class="px-3 py-2">{{ $ca->ten }} ({{ $ca->ma }})</td><td class="text-center">{{ $tenLoai[$ca->loai] ?? 'Hành chính' }}</td><td class="text-center">{{ substr($ca->gio_bat_dau,0,5) }} – {{ substr($ca->gio_ket_thuc,0,5) }}</td><td class="text-center">{{ $ca->so_gio_lam_viec }}</td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <h2 class="font-semibold mb-3">Yêu cầu đổi ca</h2>
        @forelse($yeuCaus as $yc)
            <div class="border-t py-3 flex flex-wrap justify-between gap-2 text-sm">
                <div>
                    <b>{{ trim(($yc->nguoiDung->hoSo->ho ?? '').' '.($yc->nguoiDung->hoSo->ten ?? '')) }}</b>
                    đổi ngày {{ $yc->ngay->format('d/m/Y') }}
                    từ {{ $yc->caHienTai->ten ?? 'chưa xếp' }} sang {{ $yc->caMoi->ten ?? '' }}.
                    Lý do: {{ $yc->ly_do }}.
                    @php $tenTt = ['cho_duyet' => 'Chờ duyệt', 'da_duyet' => 'Đã duyệt', 'tu_choi' => 'Từ chối']; @endphp
                    <span class="ml-1 px-2 py-0.5 rounded-full text-xs {{ $yc->trang_thai === 'da_duyet' ? 'bg-green-100 text-green-700' : ($yc->trang_thai === 'tu_choi' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">{{ $tenTt[$yc->trang_thai] ?? $yc->trang_thai }}</span>
                </div>
                @if($yc->trang_thai === 'cho_duyet')
                <form method="POST" action="{{ route('admin.ca-lam-viec.duyet', $yc->id) }}" class="flex gap-2">
                    @csrf
                    <button name="ket_qua" value="dong_y" class="px-3 py-1 rounded bg-emerald-600 text-white">Duyệt</button>
                    <button name="ket_qua" value="tu_choi" class="px-3 py-1 rounded bg-red-600 text-white">Từ chối</button>
                </form>
                @endif
            </div>
        @empty
            <p class="text-gray-500 text-sm">Chưa có yêu cầu.</p>
        @endforelse
    </div>
</div>
@endsection
