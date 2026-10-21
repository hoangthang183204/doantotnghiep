<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CaLamViec;
use App\Models\NguoiDung;
use App\Models\PhanCongCa;
use App\Models\YeuCauDoiCa;
use Illuminate\Http\Request;

class CaLamViecController extends Controller
{
    public function index()
    {
        $cas = CaLamViec::orderBy('gio_bat_dau')->get();
        $yeuCaus = YeuCauDoiCa::with(['nguoiDung.hoSo', 'caHienTai', 'caMoi'])
            ->orderByDesc('id')
            ->limit(50)
            ->get();
        return view('admin.ca-lam-viec.index', compact('cas', 'yeuCaus'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ten' => 'required|string|max:100',
            'ma' => 'required|string|max:30',
            'loai' => 'required|in:sang,chieu,toi,gay,hanh_chinh',
            'gio_bat_dau' => 'required',
            'gio_ket_thuc' => 'required',
            'so_gio_lam_viec' => 'required|numeric|min:1|max:16',
        ]);
        $data['trang_thai'] = true;
        CaLamViec::create($data);
        return back()->with('success', 'Đã thêm ca làm việc.');
    }

    public function phanCa(Request $request)
    {
        $tuNgay = $request->input('tu_ngay', now()->startOfWeek()->toDateString());
        $denNgay = $request->input('den_ngay', now()->endOfWeek()->toDateString());
        $batDau = \Carbon\Carbon::parse($tuNgay)->startOfDay();
        $ketThuc = \Carbon\Carbon::parse($denNgay)->startOfDay();
        if ($ketThuc->lt($batDau)) {
            $ketThuc = $batDau->copy();
        }
        if ($batDau->diffInDays($ketThuc) > 13) {
            $ketThuc = $batDau->copy()->addDays(13);
        }
        $tuNgay = $batDau->toDateString();
        $denNgay = $ketThuc->toDateString();
        $ngayList = [];
        for ($ngay = $batDau->copy(); $ngay->lte($ketThuc); $ngay->addDay()) {
            $ngayList[] = $ngay->copy();
        }
        $cas = CaLamViec::where('trang_thai', true)->orderBy('gio_bat_dau')->get();
        $nhanViens = NguoiDung::with('hoSo')->where('trang_thai', 1)->orderBy('id')->limit(200)->get();
        $phanCongs = PhanCongCa::with('ca')
            ->whereBetween('ngay', [$tuNgay, $denNgay])
            ->get()
            ->groupBy(fn ($row) => $row->nguoi_dung_id . '|' . $row->ngay->toDateString());

        return view('admin.ca-lam-viec.phan-ca', compact('tuNgay', 'denNgay', 'cas', 'nhanViens', 'phanCongs', 'ngayList'));
    }

    public function storePhanCa(Request $request)
    {
        $data = $request->validate([
            'nguoi_dung_id' => 'required|integer',
            'ca_lam_viec_id' => 'nullable|integer',
            'ngay' => 'required|date',
        ]);
        if (empty($data['ca_lam_viec_id'])) {
            PhanCongCa::where('nguoi_dung_id', $data['nguoi_dung_id'])->whereDate('ngay', $data['ngay'])->delete();
            return back()->with('success', 'Đã bỏ ca ngày này.');
        }
        PhanCongCa::updateOrCreate(
            ['nguoi_dung_id' => $data['nguoi_dung_id'], 'ngay' => $data['ngay']],
            ['ca_lam_viec_id' => $data['ca_lam_viec_id']]
        );
        return back()->with('success', 'Đã xếp ca.');
    }

    public function duyetDoiCa(Request $request, $id)
    {
        $yeuCau = YeuCauDoiCa::findOrFail($id);
        $chapNhan = $request->input('ket_qua') === 'dong_y';
        $yeuCau->update([
            'trang_thai' => $chapNhan ? 'da_duyet' : 'tu_choi',
            'nguoi_duyet_id' => auth()->id(),
            'thoi_gian_duyet' => now(),
            'ly_do_tu_choi' => $chapNhan ? null : $request->input('ly_do_tu_choi'),
        ]);
        if ($chapNhan) {
            PhanCongCa::updateOrCreate(
                ['nguoi_dung_id' => $yeuCau->nguoi_dung_id, 'ngay' => $yeuCau->ngay->toDateString()],
                ['ca_lam_viec_id' => $yeuCau->ca_moi_id]
            );
        }
        return back()->with('success', $chapNhan ? 'Đã duyệt đổi ca.' : 'Đã từ chối đổi ca.');
    }
}
