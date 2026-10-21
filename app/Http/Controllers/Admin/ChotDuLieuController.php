<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChotDuLieuThang;
use App\Support\VaiTroNghiepVu;
use Illuminate\Http\Request;

class ChotDuLieuController extends Controller
{
    public function index()
    {
        abort_unless(VaiTroNghiepVu::laHr(auth()->user()) || VaiTroNghiepVu::laQuanTri(auth()->user()), 403);

        $nam = (int) request('nam', now()->year);
        $ds = ChotDuLieuThang::with('nguoiChot.hoSo')
            ->where('nam', $nam)
            ->orderByDesc('thang')
            ->get();

        return view('admin.chot-du-lieu.index', compact('nam', 'ds'));
    }

    public function store(Request $request)
    {
        abort_unless(VaiTroNghiepVu::laHr(auth()->user()) || VaiTroNghiepVu::laQuanTri(auth()->user()), 403);

        $data = $request->validate([
            'thang' => 'required|integer|between:1,12',
            'nam' => 'required|integer|min:2000|max:2100',
            'ghi_chu' => 'nullable|string|max:255',
        ]);

        ChotDuLieuThang::updateOrCreate(
            ['thang' => $data['thang'], 'nam' => $data['nam']],
            [
                'trang_thai' => 'da_chot_hr',
                'nguoi_chot_id' => auth()->id(),
                'thoi_gian_chot' => now(),
                'ghi_chu' => $data['ghi_chu'] ?? null,
            ]
        );

        VaiTroNghiepVu::thongBaoCho(
            ['ke_toan'],
            'Đã chốt dữ liệu tháng ' . $data['thang'] . '/' . $data['nam'],
            'HR đã khóa sổ chấm công, tăng ca, nghỉ phép và thưởng/kỷ luật tháng ' . $data['thang'] . '. Kế toán có thể tính lương.',
            route('admin.bang-luong.create', ['thang' => $data['thang'], 'nam' => $data['nam']])
        );

        return back()->with('success', 'Đã chốt dữ liệu tháng ' . $data['thang'] . '/' . $data['nam'] . ' và báo cho kế toán.');
    }
}
