<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HangMucChecklist;
use App\Models\MauChecklist;
use App\Models\NguoiDung;
use App\Models\PhieuChecklist;
use App\Models\ViecChecklist;
use Illuminate\Http\Request;

class ChecklistNhanSuController extends Controller
{
    public function index()
    {
        $maus = MauChecklist::with('hangMucs')->get();
        $phieus = PhieuChecklist::with(['nguoiDung.hoSo', 'viecs'])->orderByDesc('id')->limit(40)->get();
        $nhanViens = NguoiDung::with('hoSo')->where('trang_thai', 1)->orderBy('id')->get();
        $nguoiPhuTrachs = $nhanViens;
        return view('admin.checklist.index', compact('maus', 'phieus', 'nhanViens', 'nguoiPhuTrachs'));
    }

    public function storePhieu(Request $request)
    {
        $data = $request->validate([
            'nguoi_dung_id' => 'required|integer',
            'loai' => 'required|in:onboarding,offboarding',
            'ngay_bat_dau' => 'nullable|date',
        ]);
        $phieu = PhieuChecklist::create([
            'nguoi_dung_id' => $data['nguoi_dung_id'],
            'loai' => $data['loai'],
            'ngay_bat_dau' => $data['ngay_bat_dau'] ?? now()->toDateString(),
            'trang_thai' => 'dang_lam',
        ]);
        $mau = MauChecklist::with('hangMucs')->where('loai', $data['loai'])->first();
        foreach ($mau?->hangMucs ?? [] as $hang) {
            ViecChecklist::create([
                'phieu_checklist_id' => $phieu->id,
                'ten' => $hang->ten,
                'trang_thai' => 'chua_xong',
            ]);
        }
        return redirect()->route('admin.checklist.show', $phieu->id)->with('success', 'Đã mở checklist.');
    }

    public function show($id)
    {
        $phieu = PhieuChecklist::with(['nguoiDung.hoSo', 'viecs.nguoiPhuTrach.hoSo'])->findOrFail($id);
        $nguoiPhuTrachs = NguoiDung::with('hoSo')->where('trang_thai', 1)->orderBy('id')->get();
        return view('admin.checklist.show', compact('phieu', 'nguoiPhuTrachs'));
    }

    public function storeViec(Request $request, $id)
    {
        $data = $request->validate([
            'ten' => 'required|string|max:200',
            'nguoi_phu_trach_id' => 'nullable|integer',
            'han_hoan_thanh' => 'nullable|date',
        ]);
        ViecChecklist::create([
            'phieu_checklist_id' => $id,
            'ten' => $data['ten'],
            'nguoi_phu_trach_id' => $data['nguoi_phu_trach_id'] ?? null,
            'han_hoan_thanh' => $data['han_hoan_thanh'] ?? null,
            'trang_thai' => 'chua_xong',
        ]);
        return back()->with('success', 'Đã thêm việc.');
    }

    public function capNhatViec(Request $request, $id)
    {
        $viec = ViecChecklist::findOrFail($id);
        $viec->update([
            'trang_thai' => $request->input('trang_thai', $viec->trang_thai),
            'nguoi_phu_trach_id' => $request->input('nguoi_phu_trach_id') ?: null,
            'han_hoan_thanh' => $request->input('han_hoan_thanh') ?: $viec->han_hoan_thanh,
            'ghi_chu' => $request->input('ghi_chu'),
        ]);
        $phieu = $viec->phieu ?? PhieuChecklist::find($viec->phieu_checklist_id);
        if ($phieu) {
            $con = $phieu->viecs()->where('trang_thai', '!=', 'xong')->exists();
            $phieu->update(['trang_thai' => $con ? 'dang_lam' : 'hoan_thanh']);
        }
        return back()->with('success', 'Đã cập nhật việc.');
    }
}
