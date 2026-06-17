<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Luong;
use Illuminate\Http\Request;
use App\Models\NguoiDung;
use App\Models\HopDongLaoDong;

class LuongController extends Controller
{
   public function index()
{
    $luongs = \App\Models\Luong::with([
        'nguoiDung',
        'hopDongLaoDong'
    ])->get();

    return view('admin.luong.index', compact('luongs'));
}
public function show($id)
{
    $luong = Luong::with(['nguoiDung', 'hopDong'])->findOrFail($id);

    return view('admin.luong.show', compact('luong'));
}
public function create()
{
    $nguoiDungs = \App\Models\NguoiDung::all();
    $hopDongs = \App\Models\HopDongLaoDong::all();

    return view('admin.luong.create', compact('nguoiDungs', 'hopDongs'));
}
public function store(Request $request)
{
    $request->validate([
        'nguoi_dung_id' => 'required',
        'hop_dong_lao_dong_id' => 'required',
        'luong_co_ban' => 'required|numeric',
        'phu_cap' => 'nullable|numeric',
        'tien_thuong' => 'nullable|numeric',
        'tien_phat' => 'nullable|numeric',
    ]);

    \App\Models\Luong::create([
        'nguoi_dung_id' => $request->nguoi_dung_id,
        'hop_dong_lao_dong_id' => $request->hop_dong_lao_dong_id,
        'luong_co_ban' => $request->luong_co_ban,
        'phu_cap' => $request->phu_cap ?? 0,
        'tien_thuong' => $request->tien_thuong ?? 0,
        'tien_phat' => $request->tien_phat ?? 0,
    ]);

    return redirect()->route('admin.luong.index')
        ->with('success', 'Thêm lương thành công!');
}
public function edit($id)
{
    $luong = Luong::findOrFail($id);

    $nhanViens = NguoiDung::all();
    $hopDongs = HopDongLaoDong::all();

    return view('admin.luong.edit', compact('luong', 'nhanViens', 'hopDongs'));
}
public function update(Request $request, $id)
{
    $request->validate([
        'nguoi_dung_id' => 'required',
        'hop_dong_id' => 'required',
        'luong_co_ban' => 'required|numeric',
        'phu_cap' => 'nullable|numeric',
        'tien_thuong' => 'nullable|numeric',
        'tien_phat' => 'nullable|numeric',
    ]);

    $luong = Luong::findOrFail($id);

    $luong->update([
        'nguoi_dung_id' => $request->nguoi_dung_id,
        'hop_dong_lao_dong_id' => $request->hop_dong_id,
        'luong_co_ban' => $request->luong_co_ban,
        'phu_cap' => $request->phu_cap ?? 0,
        'tien_thuong' => $request->tien_thuong ?? 0,
        'tien_phat' => $request->tien_phat ?? 0,
    ]);

    return redirect()->route('admin.luong.index')
        ->with('success', 'Cập nhật lương thành công');
}
}