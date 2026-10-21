<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CapPhatTaiSan;
use App\Models\NguoiDung;
use App\Models\TaiSan;
use Illuminate\Http\Request;

class TaiSanController extends Controller
{
    public function index()
    {
        $taiSans = TaiSan::with('capPhatHienTai.nguoiDung.hoSo')->orderByDesc('id')->get();
        $nhanViens = NguoiDung::with('hoSo')->where('trang_thai', 1)->orderBy('id')->get();
        return view('admin.tai-san.index', compact('taiSans', 'nhanViens'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ma' => 'required|string|max:50|unique:tai_san,ma',
            'ten' => 'required|string|max:150',
            'loai' => 'required|in:laptop,dien_thoai,the,dong_phuc,khac',
            'serial' => 'nullable|string|max:100',
            'ghi_chu' => 'nullable|string|max:255',
        ]);
        $data['tinh_trang'] = 'san_sang';
        TaiSan::create($data);
        return back()->with('success', 'Đã thêm tài sản.');
    }

    public function capPhat(Request $request, $id)
    {
        $taiSan = TaiSan::findOrFail($id);
        $data = $request->validate([
            'nguoi_dung_id' => 'required|integer',
            'ngay_cap' => 'required|date',
            'ghi_chu' => 'nullable|string|max:255',
        ]);
        CapPhatTaiSan::create([
            'tai_san_id' => $taiSan->id,
            'nguoi_dung_id' => $data['nguoi_dung_id'],
            'ngay_cap' => $data['ngay_cap'],
            'nguoi_cap_id' => auth()->id(),
            'ghi_chu' => $data['ghi_chu'] ?? null,
            'trang_thai' => 'dang_cap',
        ]);
        $taiSan->update(['tinh_trang' => 'dang_cap']);
        return back()->with('success', 'Đã cấp phát.');
    }

    public function thuHoi($id)
    {
        $cap = CapPhatTaiSan::where('tai_san_id', $id)->where('trang_thai', 'dang_cap')->latest('id')->firstOrFail();
        $cap->update(['trang_thai' => 'da_thu', 'ngay_thu' => now()->toDateString()]);
        $cap->taiSan()->update(['tinh_trang' => 'san_sang']);
        return back()->with('success', 'Đã thu hồi tài sản.');
    }
}
