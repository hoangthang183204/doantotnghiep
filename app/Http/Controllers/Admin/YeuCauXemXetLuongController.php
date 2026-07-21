<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\YeuCauXemXetLuong;
use Illuminate\Http\Request;

class YeuCauXemXetLuongController extends Controller
{
    public function index()
    {
        $yeuCaus = YeuCauXemXetLuong::with([
            'nguoiDung.ho_so',
            'luongNhanVien.bangLuong'
        ])
        ->latest()
        ->paginate(10);

        return view('admin.yeu-cau-luong.index', compact('yeuCaus'));
    }

    public function show($id)
    {
        $yeuCau = YeuCauXemXetLuong::with([
            'nguoiDung.ho_so',
            'luongNhanVien.bangLuong'
        ])->findOrFail($id);

        return view('admin.yeu-cau-luong.show', compact('yeuCau'));
    }

   public function duyet($id)
{
    $yeuCau = YeuCauXemXetLuong::findOrFail($id);

    $yeuCau->update([
        'trang_thai' => 'da_duyet',
    ]);

    return redirect()
        ->route('admin.yeu-cau-luong.show', $id)
        ->with('success', 'Đã duyệt yêu cầu.');
}

   public function tuChoi(Request $request, $id)
{
    $yeuCau = YeuCauXemXetLuong::findOrFail($id);

    $yeuCau->update([
        'trang_thai' => 'tu_choi',
    ]);

    return redirect()
        ->route('admin.yeu-cau-luong.show', $id)
        ->with('success', 'Đã từ chối yêu cầu.');
}
}