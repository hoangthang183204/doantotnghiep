<?php
// app/Http/Controllers/TruongPhong/DashboardController.php

namespace App\Http\Controllers\TruongPhong;

use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use App\Models\ChamCong;
use App\Models\DonXinNghi;
use App\Models\DangKyTangCa;
use App\Models\YeuCauDieuChinhCong;
use App\Models\PhongBan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardTruongPhongController extends Controller
{
    public function index(Request $request)
    {
        // =============================================
        // 1️⃣ LẤY THÔNG TIN TRƯỞNG PHÒNG TỪ REQUEST
        // =============================================
        $user = Auth::user();
        $phongBan = $request->input('phong_ban');
        $phongBanId = $request->input('phong_ban_id');

        // Nếu request chưa có, lấy từ user
        if (!$phongBan) {
            $phongBan = $user->phongBan;
            $phongBanId = $user->phong_ban_id;
        }

        // Nếu vẫn chưa có, tìm từ bảng phong_ban
        if (!$phongBan) {
            $phongBan = PhongBan::where('truong_phong_id', $user->id)->first();
            if ($phongBan) {
                $phongBanId = $phongBan->id;
            }
        }

        // =============================================
        // 2️⃣ LẤY DANH SÁCH NHÂN VIÊN TRONG PHÒNG
        // =============================================
        $nhanVienIds = [];
        $soNhanVien = 0;

        if ($phongBanId) {
            $nhanVienIds = NguoiDung::where('phong_ban_id', $phongBanId)
                ->where('trang_thai', 1)
                ->where('id', '!=', $user->id)  // Không tính chính trưởng phòng
                ->pluck('id')
                ->toArray();
            
            $soNhanVien = count($nhanVienIds);
        }

        // =============================================
        // 3️⃣ THỐNG KÊ CHẤM CÔNG HÔM NAY
        // =============================================
        $today = Carbon::today();
        $daChamCong = 0;
        $chuaChamCong = 0;

        if (!empty($nhanVienIds)) {
            $daChamCong = ChamCong::whereIn('nguoi_dung_id', $nhanVienIds)
                ->whereDate('ngay_cham_cong', $today)
                ->count();
            
            $chuaChamCong = $soNhanVien - $daChamCong;
        }

        // =============================================
        // 4️⃣ THỐNG KÊ ĐƠN CHỜ DUYỆT
        // =============================================
        $donChoDuyet = [
            'nghi_phep' => 0,
            'tang_ca' => 0,
            'chinh_cong' => 0,
        ];

        if (!empty($nhanVienIds)) {
            $donChoDuyet['nghi_phep'] = DonXinNghi::whereIn('nguoi_dung_id', $nhanVienIds)
                ->where('trang_thai', 'cho_duyet')
                ->count();
            
            $donChoDuyet['tang_ca'] = DangKyTangCa::whereIn('nguoi_dung_id', $nhanVienIds)
                ->where('trang_thai', 'cho_duyet')
                ->count();
            
            $donChoDuyet['chinh_cong'] = YeuCauDieuChinhCong::whereIn('nguoi_dung_id', $nhanVienIds)
                ->where('trang_thai', 'cho_duyet')
                ->count();
        }

        // =============================================
        // 5️⃣ BIỂU ĐỒ CHẤM CÔNG THEO THÁNG
        // =============================================
        $year = Carbon::now()->year;
        $chartAttendance = [];
        $chartLabels = [];

        for ($i = 1; $i <= 12; $i++) {
            $chartLabels[] = 'T' . $i;
            
            if (!empty($nhanVienIds)) {
                $chartAttendance[] = ChamCong::whereIn('nguoi_dung_id', $nhanVienIds)
                    ->whereMonth('ngay_cham_cong', $i)
                    ->whereYear('ngay_cham_cong', $year)
                    ->count();
            } else {
                $chartAttendance[] = 0;
            }
        }

        // =============================================
        // 6️⃣ TOP 5 NHÂN VIÊN CHẤM CÔNG NHIỀU NHẤT
        // =============================================
        $topNhanVien = [];

        if (!empty($nhanVienIds)) {
            $topNhanVien = ChamCong::whereIn('nguoi_dung_id', $nhanVienIds)
                ->whereYear('ngay_cham_cong', $year)
                ->select('nguoi_dung_id', DB::raw('COUNT(*) as total'))
                ->groupBy('nguoi_dung_id')
                ->orderByDesc('total')
                ->limit(5)
                ->with(['nguoiDung.hoSo'])
                ->get()
                ->map(function ($item) {
                    $nv = $item->nguoiDung;
                    $hoSo = $nv->hoSo;
                    return [
                        'id' => $nv->id,
                        'ho_ten' => $hoSo ? ($hoSo->ho . ' ' . $hoSo->ten) : $nv->ten_dang_nhap,
                        'ma_nhan_vien' => $hoSo->ma_nhan_vien ?? null,
                        'tong_cham_cong' => $item->total,
                    ];
                });
        }

        // =============================================
        // 7️⃣ DANH SÁCH NHÂN VIÊN TRONG PHÒNG
        // =============================================
        $nhanViens = NguoiDung::with(['hoSo', 'chucVu'])
            ->where('phong_ban_id', $phongBanId)
            ->where('trang_thai', 1)
            ->where('id', '!=', $user->id)
            ->orderBy('id')
            ->paginate(10);

        return view('truong-phong.dashboard', compact(
            'phongBan',
            'soNhanVien',
            'daChamCong',
            'chuaChamCong',
            'donChoDuyet',
            'chartLabels',
            'chartAttendance',
            'topNhanVien',
            'nhanViens'
        ));
    }

    /**
     * API: Lấy danh sách nhân viên trong phòng (cho AJAX)
     */
    public function getNhanVien(Request $request)
    {
        $user = Auth::user();
        $phongBanId = $request->input('phong_ban_id', $user->phong_ban_id);

        $nhanViens = NguoiDung::with(['hoSo', 'chucVu'])
            ->where('phong_ban_id', $phongBanId)
            ->where('trang_thai', 1)
            ->where('id', '!=', $user->id)
            ->select('id', 'ten_dang_nhap', 'email', 'phong_ban_id', 'chuc_vu_id')
            ->get()
            ->map(function ($nv) {
                $hoSo = $nv->hoSo;
                return [
                    'id' => $nv->id,
                    'ho_ten' => $hoSo ? ($hoSo->ho . ' ' . $hoSo->ten) : $nv->ten_dang_nhap,
                    'ma_nhan_vien' => $hoSo->ma_nhan_vien ?? null,
                    'chuc_vu' => $nv->chucVu->ten ?? 'Chưa có',
                    'email' => $nv->email,
                ];
            });

        return response()->json($nhanViens);
    }
}