<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use App\Models\ChamCong;
use App\Models\DonXinNghi;

class DashboardController extends Controller
{
    public function index()
    {
        $totalNhanVien = NguoiDung::where('trang_thai', 1)->count();
        $homNayCoMat = ChamCong::whereDate('ngay_cham_cong', today())->whereNotNull('gio_vao')->count();
        $dangNghiPhep = DonXinNghi::where('trang_thai', 'da_duyet')
            ->whereDate('ngay_bat_dau', '<=', today())
            ->whereDate('ngay_ket_thuc', '>=', today())
            ->count();
        $diMuonHomNay = ChamCong::whereDate('ngay_cham_cong', today())->where('trang_thai', 'di_muon')->count();
        
        $chamCongs = ChamCong::with('nguoi_dung.ho_so')->whereDate('ngay_cham_cong', today())->limit(10)->get();
        $donXinNghis = DonXinNghi::with('nguoi_dung.ho_so')->orderBy('created_at', 'desc')->limit(5)->get();
        
        return view('admin.dashboard.index', compact(
            'totalNhanVien', 'homNayCoMat', 'dangNghiPhep', 'diMuonHomNay',
            'chamCongs', 'donXinNghis'
        ));
    }
}