<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\CaLamViec;
use App\Models\PhanCongCa;
use App\Models\YeuCauDoiCa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CaLamViecController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cas = CaLamViec::where('trang_thai', true)->orderBy('gio_bat_dau')->get();
        $lich = PhanCongCa::with('ca')
            ->where('nguoi_dung_id', $user->id)
            ->where('ngay', '>=', now()->startOfWeek()->toDateString())
            ->orderBy('ngay')
            ->get();
        $yeuCaus = YeuCauDoiCa::with(['caHienTai', 'caMoi'])
            ->where('nguoi_dung_id', $user->id)
            ->orderByDesc('id')
            ->limit(20)
            ->get();

        return view('employee.ca-lam-viec.index', compact('cas', 'lich', 'yeuCaus'));
    }

    public function doiCa(Request $request)
    {
        $data = $request->validate([
            'ngay' => 'required|date',
            'ca_moi_id' => 'required|integer',
            'ly_do' => 'required|string|max:255',
        ]);
        $hienTai = PhanCongCa::where('nguoi_dung_id', Auth::id())
            ->whereDate('ngay', $data['ngay'])
            ->first();

        YeuCauDoiCa::create([
            'nguoi_dung_id' => Auth::id(),
            'ngay' => $data['ngay'],
            'ca_hien_tai_id' => $hienTai->ca_lam_viec_id ?? null,
            'ca_moi_id' => $data['ca_moi_id'],
            'ly_do' => $data['ly_do'],
            'trang_thai' => 'cho_duyet',
        ]);

        return back()->with('success', 'Đã gửi yêu cầu đổi ca.');
    }
}
