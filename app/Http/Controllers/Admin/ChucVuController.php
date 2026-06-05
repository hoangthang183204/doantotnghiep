<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChucVu;
use App\Models\PhongBan;
use Illuminate\Http\Request;

class ChucVuController extends Controller
{
    public function index()
    {
        $chucVus = ChucVu::with('phong_ban')
            ->orderBy('id', 'asc')
            ->paginate(10);

        return view('admin.chuc-vu.index', compact('chucVus'));
    }

    public function create()
    {
        $phongBans = PhongBan::where('trang_thai', 1)->get();

        return view('admin.chuc-vu.create', compact('phongBans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten' => 'required',
            'ma' => 'required|unique:chuc_vu,ma',
            'phong_ban_id' => 'required'
        ]);

        ChucVu::create($request->all());

        return redirect()
            ->route('admin.chuc-vu.index')
            ->with('success', 'Thêm chức vụ thành công');
    }

    public function edit($id)
    {
        $chucVu = ChucVu::findOrFail($id);

        $phongBans = PhongBan::where('trang_thai', 1)->get();

        return view('admin.chuc-vu.edit', compact(
            'chucVu',
            'phongBans'
        ));
    }

    public function update(Request $request, $id)
    {
        $chucVu = ChucVu::findOrFail($id);

        $request->validate([
            'ten' => 'required',
            'ma' => 'required|unique:chuc_vu,ma,' . $id,
            'phong_ban_id' => 'required'
        ]);

        $chucVu->update($request->all());

        return redirect()
            ->route('admin.chuc-vu.index')
            ->with('success', 'Cập nhật thành công');
    }

    public function destroy($id)
    {
        ChucVu::findOrFail($id)->delete();

        return back()->with(
            'success',
            'Xóa chức vụ thành công'
        );
    }
}
