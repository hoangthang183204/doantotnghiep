<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UngVien;
use App\Models\TinTuyenDung;
use Illuminate\Http\Request;

class UngVienController extends Controller
{
    // ======================
    // DANH SÁCH
    // ======================
    public function index(Request $request)
    {
        $query = UngVien::with([
            'tinTuyenDung.phongBan',
            'tinTuyenDung.chucVu'
        ]);

        if ($request->keyword) {
            $query->where(function ($q) use ($request) {
                $q->where('ho', 'like', "%{$request->keyword}%")
                  ->orWhere('ten', 'like', "%{$request->keyword}%")
                  ->orWhere('email', 'like', "%{$request->keyword}%")
                  ->orWhere('so_dien_thoai', 'like', "%{$request->keyword}%")
                  ->orWhere('ma_ho_so', 'like', "%{$request->keyword}%");
            });
        }

        if ($request->trang_thai) {
            $query->where('trang_thai', $request->trang_thai);
        }

        if ($request->tin_tuyen_dung_id) {
            $query->where('tin_tuyen_dung_id', $request->tin_tuyen_dung_id);
        }

        $ungViens = $query->orderByDesc('id')->paginate(10);

        $tinTuyenDungs = TinTuyenDung::select('id', 'tieu_de')->get();

        return view('admin.ung-vien.index', compact('ungViens', 'tinTuyenDungs'));
    }
    // ======================
    // XEM
    // ======================
    public function show($id)
    {
    $ungVien = UngVien::with([
        'tinTuyenDung.phongBan',
        'tinTuyenDung.chucVu'
    ])->findOrFail($id);

    return view('admin.ung-vien.show', compact('ungVien'));
    }
    // ======================
    // FORM THÊM
    // ======================
    public function create()
    {
        $tinTuyenDungs = TinTuyenDung::select('id', 'tieu_de')->get();
        return view('admin.ung-vien.create', compact('tinTuyenDungs'));
    }

    // ======================
    // LƯU THÊM
    // ======================
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'ho' => 'required|string|max:255',
    //         'ten' => 'required|string|max:255',
    //         'email' => 'required|email|unique:ung_vien,email',
    //         'so_dien_thoai' => 'required|string|max:20',
    //         'ma_ho_so' => 'nullable|string|max:50',
    //         'luong_mong_muon' => 'nullable|numeric',
    //         'tin_tuyen_dung_id' => 'nullable|exists:tin_tuyen_dung,id',
    //     ]);

    //     UngVien::create([
    //         'ho' => $request->ho,
    //         'ten' => $request->ten,
    //         'email' => $request->email,
    //         'so_dien_thoai' => $request->so_dien_thoai,
    //         'ma_ho_so' => $request->ma_ho_so,
    //         'luong_mong_muon' => $request->luong_mong_muon,
    //         'tin_tuyen_dung_id' => $request->tin_tuyen_dung_id,
    //         'trang_thai' => 'moi_nop',
    //     ]);

    //     return redirect()->route('admin.ung_vien.index')
    //         ->with('success', 'Thêm ứng viên thành công!');
    // }
    public function store(Request $request)
{
    try {

        $request->validate([
            'ho' => 'required|string|max:255',
            'ten' => 'required|string|max:255',
            'email' => 'required|email|unique:ung_vien,email',
            'so_dien_thoai' => 'required|string|max:20',
            'ma_ho_so' => 'nullable|string|max:50',
            'luong_mong_muon' => 'nullable|numeric',
            'tin_tuyen_dung_id' => 'nullable|exists:tin_tuyen_dung,id',
        ]);

        UngVien::create([
            'ho' => $request->ho,
            'ten' => $request->ten,
            'email' => $request->email,
            'so_dien_thoai' => $request->so_dien_thoai,
            'ma_ho_so' => $request->ma_ho_so,
            'luong_mong_muon' => $request->luong_mong_muon,
            'tin_tuyen_dung_id' => $request->tin_tuyen_dung_id,
            'trang_thai' => 'moi_nop',
        ]);

        return redirect()->route('admin.ung_vien.index')
            ->with('success', 'Thêm ứng viên thành công!');

    } catch (\Exception $e) {

        dd($e->getMessage());

    }
}

    // ======================
    // FORM SỬA
    // ======================
    public function edit($id)
    {
        $ungVien = UngVien::findOrFail($id);
        $tinTuyenDungs = TinTuyenDung::select('id', 'tieu_de')->get();

        return view('admin.ung-vien.edit', compact('ungVien', 'tinTuyenDungs'));
    }

    // ======================
    // CẬP NHẬT
    // ======================
    public function update(Request $request, $id)
    {
        $request->validate([
            'ho' => 'required|string|max:255',
            'ten' => 'required|string|max:255',
            'email' => 'required|email',
            'so_dien_thoai' => 'required|string|max:20',
        ]);

        $ungVien = UngVien::findOrFail($id);

        $ungVien->update([
            'ho' => $request->ho,
            'ten' => $request->ten,
            'email' => $request->email,
            'so_dien_thoai' => $request->so_dien_thoai,
            'ma_ho_so' => $request->ma_ho_so,
            'luong_mong_muon' => $request->luong_mong_muon,
            'tin_tuyen_dung_id' => $request->tin_tuyen_dung_id ?? $ungVien->tin_tuyen_dung_id,
        ]);

        return redirect()->route('admin.ung_vien.index')
            ->with('success', 'Cập nhật ứng viên thành công!');
    }

    // ======================
    // XÓA (OPTIONAL)
    // ======================
    public function destroy($id)
    {
    $ungVien = UngVien::findOrFail($id);
    $ungVien->delete();

    return redirect()->route('admin.ung_vien.index')
        ->with('success', 'Xóa ứng viên thành công!');
    }
    // ======================
    // GỬI EMAIL (OPTIONAL)
    // ======================
    public function createEmail()
    {
    $ungViens = UngVien::with('tinTuyenDung')
        ->orderByDesc('id')
        ->get();

    return view('admin.ung-vien.email.create', compact('ungViens'));
    }
    public function sendEmail(Request $request)
    {
    $request->validate([
        'ung_vien_id' => 'required|exists:ung_vien,id',
        'thoi_gian' => 'required',
        'dia_diem' => 'required',
    ]);

    $ungVien = UngVien::findOrFail($request->ung_vien_id);

    $emails = session()->get('email_phong_van', []);

    $emails[] = [
        'ung_vien' => $ungVien->ho . ' ' . $ungVien->ten,
        'email' => $ungVien->email,
        'thoi_gian' => $request->thoi_gian,
        'dia_diem' => $request->dia_diem,
        'ngay_gui' => now()->format('d/m/Y H:i'),
    ];

    session()->put('email_phong_van', $emails);

    $ungVien->update([
        'trang_thai' => 'phong_van'
    ]);

    return redirect()
        ->route('admin.ung_vien.email.index')
        ->with('success', 'Gửi email phỏng vấn thành công');
    }
    public function emailList()
    {
    $emails = session()->get('email_phong_van', []);

    return view('admin.ung-vien.email.index', compact('emails'));
    }
}