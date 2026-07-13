<?php
// app/Http/Controllers/TruongPhong/NhanVienController.php

namespace App\Http\Controllers\TruongPhong;

use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use App\Models\PhongBan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NhanVienController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $phongBanId = $this->getPhongBanId($user);
        
        if (!$phongBanId) {
            return redirect()->back()->with('error', 'Bạn chưa được phân công phòng ban.');
        }
        
        $phongBan = PhongBan::find($phongBanId);
        
        $query = NguoiDung::with(['hoSo', 'chucVu'])
            ->where('phong_ban_id', $phongBanId)
            ->where('trang_thai', 1)
            ->where('id', '!=', $user->id);
        
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('ten_dang_nhap', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhereHas('hoSo', function ($hs) use ($keyword) {
                        $hs->where('ho', 'like', "%{$keyword}%")
                            ->orWhere('ten', 'like', "%{$keyword}%")
                            ->orWhere('ma_nhan_vien', 'like', "%{$keyword}%")
                            ->orWhereRaw("CONCAT(ho, ' ', ten) LIKE ?", ["%{$keyword}%"]);
                    });
            });
        }
        
        $nhanViens = $query->orderBy('id')->paginate(15);
        
        return view('truong-phong.nhan-vien.index', compact('nhanViens', 'phongBan'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $phongBanId = $this->getPhongBanId($user);
        
        if (!$phongBanId) {
            return redirect()->back()->with('error', 'Bạn chưa được phân công phòng ban.');
        }
        
        $nhanVien = NguoiDung::with(['hoSo', 'chucVu', 'phongBan'])
            ->where('phong_ban_id', $phongBanId)
            ->where('id', $id)
            ->firstOrFail();
        
        return view('truong-phong.nhan-vien.show', compact('nhanVien'));
    }

    private function getPhongBanId($user)
    {
        if ($user->phong_ban_id) {
            return $user->phong_ban_id;
        }
        $phongBan = PhongBan::where('truong_phong_id', $user->id)->first();
        return $phongBan ? $phongBan->id : null;
    }
}