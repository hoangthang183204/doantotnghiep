<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PhongBan;
use Illuminate\Http\Request;

class PhongBanController extends Controller
{
    public function index()
    {
        $phongBans = PhongBan::with('truong_phong')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.phong-ban.index', compact('phongBans'));
    }

    public function create()
    {
        return view('admin.phong-ban.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'ma_phong_ban' => 'required',
            'ten_phong_ban' => 'required',
        ]);

        PhongBan::create($request->all());

        return redirect()->route('admin.phong-ban.index')
            ->with('success', 'Thêm phòng ban thành công');
    }

    public function edit($id)
    {
        $phongBan = PhongBan::findOrFail($id);
        return view('admin.phong-ban.edit', compact('phongBan'));
    }

    public function update(Request $request, $id)
    {
        $phongBan = PhongBan::findOrFail($id);

        $request->validate([
            'ten_phong_ban' => 'required',
            'ma_phong_ban' => 'required|unique:phong_ban,ma_phong_ban,' . $id,
        ]);

        $phongBan->update($request->all());

        return redirect()->route('admin.phong-ban.index')
            ->with('success', 'Cập nhật thành công');
    }

    public function destroy($id)
    {
        PhongBan::findOrFail($id)->delete();

        return back()->with('success', 'Xóa thành công');
    }
}
