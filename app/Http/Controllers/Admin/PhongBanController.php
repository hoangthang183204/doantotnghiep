<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PhongBan;
use App\Models\NguoiDung;
use Illuminate\Http\Request;

class PhongBanController extends Controller
{
    public function index()
    {
        $phongBans = PhongBan::with('truong_phong')
            ->orderBy('id', 'asc')
            ->paginate(10);

        return view('admin.phong-ban.index', compact('phongBans'));
    }

    public function create()
    {
        $nguoiDungs = NguoiDung::where('trang_thai', 1)->get();
        
        return view('admin.phong-ban.create', compact('nguoiDungs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ma_phong_ban' => 'required|string|max:255|unique:phong_ban,ma_phong_ban',
            'ten_phong_ban' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'truong_phong_id' => 'nullable|exists:nguoi_dung,id',
            'ngan_sach' => 'nullable|numeric|min:0',
            'trang_thai' => 'boolean',
        ]);

        PhongBan::create($request->all());

        return redirect()->route('admin.phong-ban.index')
            ->with('success', 'Thêm phòng ban thành công');
    }

    public function show($id)
    {
        $phongBan = PhongBan::with(['truong_phong', 'nguoi_dungs', 'chuc_vus'])->findOrFail($id);

        return view('admin.phong-ban.show', compact('phongBan'));
    }

    public function edit($id)
    {
        $phongBan = PhongBan::findOrFail($id);
        $nguoiDungs = NguoiDung::where('trang_thai', 1)->get();

        return view('admin.phong-ban.edit', compact('phongBan', 'nguoiDungs'));
    }

    public function update(Request $request, $id)
    {
        $phongBan = PhongBan::findOrFail($id);

        $request->validate([
            'ma_phong_ban' => 'required|string|max:255|unique:phong_ban,ma_phong_ban,' . $id,
            'ten_phong_ban' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'truong_phong_id' => 'nullable|exists:nguoi_dung,id',
            'ngan_sach' => 'nullable|numeric|min:0',
            'trang_thai' => 'boolean',
        ]);

        $phongBan->update($request->all());

        return redirect()->route('admin.phong-ban.index')
            ->with('success', 'Cập nhật thành công');
    }

    public function destroy($id)
    {
        $phongBan = PhongBan::findOrFail($id);
        
        if ($phongBan->nguoi_dungs()->count() > 0) {
            return back()->with('error', 'Không thể xóa vì phòng ban đang có nhân viên.');
        }
        
        if ($phongBan->chuc_vus()->count() > 0) {
            return back()->with('error', 'Không thể xóa vì phòng ban đang có chức vụ.');
        }
        
        $phongBan->delete();

        return back()->with('success', 'Xóa thành công');
    }
}