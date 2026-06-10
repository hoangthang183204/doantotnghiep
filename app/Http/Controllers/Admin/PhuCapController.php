<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PhuCap;

class PhuCapController extends Controller
{
    public function index()
    {
        $phuCaps = PhuCap::paginate(10);

        return view('admin.phu-cap.index', compact('phuCaps'));
    }
    public function edit($id)
{
    $phuCap = PhuCap::findOrFail($id);

    return view('admin.phu-cap.edit', compact('phuCap'));
}
public function update(Request $request, $id)
{
    $phuCap = PhuCap::findOrFail($id);

    $phuCap->update([
    'ten' => $request->ten,
    'ma' => $request->ma,
    'loai_phu_cap' => $request->loai_phu_cap,
    'so_tien_mac_dinh' => $request->so_tien_mac_dinh,
    'chiu_thue' => $request->has('chiu_thue') ? 1 : 0,
    'trang_thai' => $request->trang_thai,
]);

    return redirect()
        ->route('admin.phu-cap.index')
        ->with('success', 'Cập nhật thành công');
}
public function show($id)
{
    $phuCap = PhuCap::findOrFail($id);

    return view('admin.phu-cap.show', compact('phuCap'));
}
public function destroy($id)
{
    $phuCap = PhuCap::findOrFail($id);

    $phuCap->delete();

    return redirect()
        ->route('admin.phu-cap.index')
        ->with('success', 'Xóa thành công');
}
public function create()
{
    return view('admin.phu-cap.create');
}
public function store(Request $request)
{
    $request->validate([
        'ten' => 'required',
        'ma' => 'required|unique:phu_cap,ma',
        'loai_phu_cap' => 'required',
        'so_tien_mac_dinh' => 'required|numeric',
    ]);

    PhuCap::create([
        'ten' => $request->ten,
        'ma' => $request->ma,
        'loai_phu_cap' => $request->loai_phu_cap,
        'so_tien_mac_dinh' => $request->so_tien_mac_dinh,

        // FIX QUAN TRỌNG
        'chiu_thue' => $request->has('chiu_thue') ? 1 : 0,

        'trang_thai' => $request->trang_thai ?? 1,
    ]);

    return redirect()
        ->route('admin.phu-cap.index')
        ->with('success', 'Thêm phụ cấp thành công');
}
}