<?php
// app/Http/Controllers/TruongPhong/BaoCaoController.php

namespace App\Http\Controllers\TruongPhong;

use App\Exports\TruongPhong\AttendanceReportExport;
use App\Exports\TruongPhong\LeaveReportExport;
use App\Exports\TruongPhong\OverviewReportExport;
use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use App\Models\ChamCong;
use App\Models\DonXinNghi;
use App\Models\DangKyTangCa;
use App\Models\YeuCauDieuChinhCong;
use App\Models\PhongBan;
use App\Models\LuongNhanVien;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class BaoCaoController extends Controller
{
    /**
     * Lấy ID phòng ban của trưởng phòng
     */
    private function getPhongBanId($user)
    {
        if ($user->phong_ban_id) {
            return $user->phong_ban_id;
        }
        $phongBan = PhongBan::where('truong_phong_id', $user->id)->first();
        return $phongBan ? $phongBan->id : null;
    }

    /**
     * Lấy danh sách ID nhân viên trong phòng
     */
    private function getNhanVienIds($phongBanId)
    {
        if (!$phongBanId) return [];
        return NguoiDung::where('phong_ban_id', $phongBanId)
            ->where('trang_thai', 1)
            ->pluck('id')
            ->toArray();
    }

    /**
     * 📊 TỔNG QUAN BÁO CÁO
     */
    public function overview(Request $request)
    {
        $user = Auth::user();
        $phongBanId = $this->getPhongBanId($user);

        if (!$phongBanId) {
            return redirect()->back()->with('error', 'Bạn chưa được phân công phòng ban.');
        }

        $phongBan = PhongBan::find($phongBanId);
        $nhanVienIds = $this->getNhanVienIds($phongBanId);
        $thang = $request->input('thang', Carbon::now()->month);
        $nam = $request->input('nam', Carbon::now()->year);

        // ========== THỐNG KÊ NHÂN SỰ ==========
        $tongNhanVien = count($nhanVienIds);
        $nhanVienNam = NguoiDung::whereIn('id', $nhanVienIds)
            ->whereHas('hoSo', function ($q) {
                $q->where('gioi_tinh', 'nam');
            })->count();
        $nhanVienNu = NguoiDung::whereIn('id', $nhanVienIds)
            ->whereHas('hoSo', function ($q) {
                $q->where('gioi_tinh', 'nu');
            })->count();

        // ========== THỐNG KÊ CHẤM CÔNG ==========
        $ngayTrongThang = Carbon::create($nam, $thang)->daysInMonth;
        $soNgayLam = ChamCong::whereIn('nguoi_dung_id', $nhanVienIds)
            ->whereMonth('ngay_cham_cong', $thang)
            ->whereYear('ngay_cham_cong', $nam)
            ->count();

        $soNgayDiMuon = ChamCong::whereIn('nguoi_dung_id', $nhanVienIds)
            ->whereMonth('ngay_cham_cong', $thang)
            ->whereYear('ngay_cham_cong', $nam)
            ->where('trang_thai', 'di_muon')
            ->count();

        $soNgayVeSom = ChamCong::whereIn('nguoi_dung_id', $nhanVienIds)
            ->whereMonth('ngay_cham_cong', $thang)
            ->whereYear('ngay_cham_cong', $nam)
            ->where('trang_thai', 've_som')
            ->count();

        $soNgayTangCa = ChamCong::whereIn('nguoi_dung_id', $nhanVienIds)
            ->whereMonth('ngay_cham_cong', $thang)
            ->whereYear('ngay_cham_cong', $nam)
            ->where('trang_thai', 'tang_ca')
            ->count();

        // Tỷ lệ chấm công
        $tyLeChamCong = $tongNhanVien > 0 && $ngayTrongThang > 0
            ? round(($soNgayLam / ($tongNhanVien * $ngayTrongThang)) * 100, 1)
            : 0;

        // ========== THỐNG KÊ ĐƠN NGHỈ PHÉP ==========
        $tongDonNghi = DonXinNghi::whereIn('nguoi_dung_id', $nhanVienIds)
            ->whereMonth('ngay_bat_dau', $thang)
            ->whereYear('ngay_bat_dau', $nam)
            ->count();

        $donNghiChoDuyet = DonXinNghi::whereIn('nguoi_dung_id', $nhanVienIds)
            ->whereMonth('ngay_bat_dau', $thang)
            ->whereYear('ngay_bat_dau', $nam)
            ->where('trang_thai', 'cho_duyet')
            ->count();

        $donNghiDaDuyet = DonXinNghi::whereIn('nguoi_dung_id', $nhanVienIds)
            ->whereMonth('ngay_bat_dau', $thang)
            ->whereYear('ngay_bat_dau', $nam)
            ->where('trang_thai', 'da_duyet')
            ->count();

        $donNghiTuChoi = DonXinNghi::whereIn('nguoi_dung_id', $nhanVienIds)
            ->whereMonth('ngay_bat_dau', $thang)
            ->whereYear('ngay_bat_dau', $nam)
            ->where('trang_thai', 'tu_choi')
            ->count();

        // ========== THỐNG KÊ TĂNG CA ==========
        $tongTangCa = DangKyTangCa::whereIn('nguoi_dung_id', $nhanVienIds)
            ->whereMonth('ngay_tang_ca', $thang)
            ->whereYear('ngay_tang_ca', $nam)
            ->count();

        $tangCaChoDuyet = DangKyTangCa::whereIn('nguoi_dung_id', $nhanVienIds)
            ->whereMonth('ngay_tang_ca', $thang)
            ->whereYear('ngay_tang_ca', $nam)
            ->where('trang_thai', 'cho_duyet')
            ->count();

        $tangCaDaDuyet = DangKyTangCa::whereIn('nguoi_dung_id', $nhanVienIds)
            ->whereMonth('ngay_tang_ca', $thang)
            ->whereYear('ngay_tang_ca', $nam)
            ->where('trang_thai', 'da_duyet')
            ->count();

        // ========== THỐNG KÊ CHỈNH CÔNG ==========
        $tongChinhCong = YeuCauDieuChinhCong::whereIn('nguoi_dung_id', $nhanVienIds)
            ->whereMonth('ngay', $thang)
            ->whereYear('ngay', $nam)
            ->count();

        $chinhCongChoDuyet = YeuCauDieuChinhCong::whereIn('nguoi_dung_id', $nhanVienIds)
            ->whereMonth('ngay', $thang)
            ->whereYear('ngay', $nam)
            ->where('trang_thai', 'cho_duyet')
            ->count();

        // ========== BIỂU ĐỒ CHẤM CÔNG 12 THÁNG ==========
        $chartLabels = [];
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartLabels[] = 'T' . $i;
            $chartData[] = ChamCong::whereIn('nguoi_dung_id', $nhanVienIds)
                ->whereMonth('ngay_cham_cong', $i)
                ->whereYear('ngay_cham_cong', $nam)
                ->count();
        }

        // ========== BIỂU ĐỒ ĐƠN NGHỈ PHÉP ==========
        $leaveChartLabels = [];
        $leaveChartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $leaveChartLabels[] = 'T' . $i;
            $leaveChartData[] = DonXinNghi::whereIn('nguoi_dung_id', $nhanVienIds)
                ->whereMonth('ngay_bat_dau', $i)
                ->whereYear('ngay_bat_dau', $nam)
                ->where('trang_thai', 'da_duyet')
                ->count();
        }

        return view('truong-phong.bao-cao.overview', compact(
            'phongBan',
            'thang',
            'nam',
            'tongNhanVien',
            'nhanVienNam',
            'nhanVienNu',
            'tyLeChamCong',
            'soNgayLam',
            'soNgayDiMuon',
            'soNgayVeSom',
            'soNgayTangCa',
            'tongDonNghi',
            'donNghiChoDuyet',
            'donNghiDaDuyet',
            'donNghiTuChoi',
            'tongTangCa',
            'tangCaChoDuyet',
            'tangCaDaDuyet',
            'tongChinhCong',
            'chinhCongChoDuyet',
            'chartLabels',
            'chartData',
            'leaveChartLabels',
            'leaveChartData'
        ));
    }

    /**
     * 📊 BÁO CÁO CHẤM CÔNG CHI TIẾT
     */
    public function attendance(Request $request)
    {
        $user = Auth::user();
        $phongBanId = $this->getPhongBanId($user);

        if (!$phongBanId) {
            return redirect()->back()->with('error', 'Bạn chưa được phân công phòng ban.');
        }

        $phongBan = PhongBan::find($phongBanId);
        $nhanVienIds = $this->getNhanVienIds($phongBanId);
        $thang = $request->input('thang', Carbon::now()->month);
        $nam = $request->input('nam', Carbon::now()->year);

        // Lấy danh sách nhân viên và số ngày chấm công
        $nhanViens = NguoiDung::with(['hoSo', 'chucVu'])
            ->whereIn('id', $nhanVienIds)
            ->get()
            ->map(function ($nv) use ($thang, $nam) {
                $soNgayChamCong = ChamCong::where('nguoi_dung_id', $nv->id)
                    ->whereMonth('ngay_cham_cong', $thang)
                    ->whereYear('ngay_cham_cong', $nam)
                    ->count();

                $soNgayDiMuon = ChamCong::where('nguoi_dung_id', $nv->id)
                    ->whereMonth('ngay_cham_cong', $thang)
                    ->whereYear('ngay_cham_cong', $nam)
                    ->where('trang_thai', 'di_muon')
                    ->count();

                $soNgayVeSom = ChamCong::where('nguoi_dung_id', $nv->id)
                    ->whereMonth('ngay_cham_cong', $thang)
                    ->whereYear('ngay_cham_cong', $nam)
                    ->where('trang_thai', 've_som')
                    ->count();

                $tongGioLam = ChamCong::where('nguoi_dung_id', $nv->id)
                    ->whereMonth('ngay_cham_cong', $thang)
                    ->whereYear('ngay_cham_cong', $nam)
                    ->sum('so_gio_lam');

                return [
                    'id' => $nv->id,
                    'ho_ten' => ($nv->hoSo->ho ?? '') . ' ' . ($nv->hoSo->ten ?? ''),
                    'ma_nhan_vien' => $nv->hoSo->ma_nhan_vien ?? 'N/A',
                    'chuc_vu' => $nv->chucVu->ten ?? 'N/A',
                    'so_ngay_cham_cong' => $soNgayChamCong,
                    'so_ngay_di_muon' => $soNgayDiMuon,
                    'so_ngay_ve_som' => $soNgayVeSom,
                    'tong_gio_lam' => round($tongGioLam, 2),
                ];
            })
            ->sortByDesc('so_ngay_cham_cong');

        // Thống kê tổng hợp
        $tongNhanVien = $nhanViens->count();
        $tongNgayChamCong = $nhanViens->sum('so_ngay_cham_cong');
        $tongNgayDiMuon = $nhanViens->sum('so_ngay_di_muon');
        $tongNgayVeSom = $nhanViens->sum('so_ngay_ve_som');
        $tongGioLam = $nhanViens->sum('tong_gio_lam');

        $tyLeChamCong = $tongNhanVien > 0
            ? round(($tongNgayChamCong / ($tongNhanVien * Carbon::create($nam, $thang)->daysInMonth)) * 100, 1)
            : 0;

        return view('truong-phong.bao-cao.attendance', compact(
            'phongBan',
            'thang',
            'nam',
            'nhanViens',
            'tongNhanVien',
            'tongNgayChamCong',
            'tongNgayDiMuon',
            'tongNgayVeSom',
            'tongGioLam',
            'tyLeChamCong'
        ));
    }

    /**
     * 📊 BÁO CÁO NGHỈ PHÉP
     */
    public function leave(Request $request)
    {
        $user = Auth::user();
        $phongBanId = $this->getPhongBanId($user);

        if (!$phongBanId) {
            return redirect()->back()->with('error', 'Bạn chưa được phân công phòng ban.');
        }

        $phongBan = PhongBan::find($phongBanId);
        $nhanVienIds = $this->getNhanVienIds($phongBanId);
        $thang = $request->input('thang', Carbon::now()->month);
        $nam = $request->input('nam', Carbon::now()->year);

        // Danh sách đơn nghỉ phép trong tháng
        $donNghis = DonXinNghi::with(['nguoiDung.hoSo', 'nguoiDung.chucVu', 'loaiNghiPhep'])
            ->whereIn('nguoi_dung_id', $nhanVienIds)
            ->whereMonth('ngay_bat_dau', $thang)
            ->whereYear('ngay_bat_dau', $nam)
            ->orderBy('ngay_bat_dau', 'desc')
            ->get();

        // Thống kê theo loại nghỉ
        $thongKeLoaiNghi = DonXinNghi::whereIn('nguoi_dung_id', $nhanVienIds)
            ->whereMonth('ngay_bat_dau', $thang)
            ->whereYear('ngay_bat_dau', $nam)
            ->select('loai_nghi_phep_id', DB::raw('COUNT(*) as so_luong'))
            ->groupBy('loai_nghi_phep_id')
            ->with('loaiNghiPhep')
            ->get();

        // Thống kê theo nhân viên
        $thongKeNhanVien = DonXinNghi::whereIn('nguoi_dung_id', $nhanVienIds)
            ->whereMonth('ngay_bat_dau', $thang)
            ->whereYear('ngay_bat_dau', $nam)
            ->select('nguoi_dung_id', DB::raw('COUNT(*) as so_luong'))
            ->groupBy('nguoi_dung_id')
            ->with('nguoiDung.hoSo')
            ->get();

        // Thống kê tổng hợp
        $tongDon = $donNghis->count();
        $tongSoNgayNghi = $donNghis->sum('so_ngay_nghi');
        $choDuyet = $donNghis->where('trang_thai', 'cho_duyet')->count();
        $daDuyet = $donNghis->where('trang_thai', 'da_duyet')->count();
        $tuChoi = $donNghis->where('trang_thai', 'tu_choi')->count();

        return view('truong-phong.bao-cao.leave', compact(
            'phongBan',
            'thang',
            'nam',
            'donNghis',
            'thongKeLoaiNghi',
            'thongKeNhanVien',
            'tongDon',
            'tongSoNgayNghi',
            'choDuyet',
            'daDuyet',
            'tuChoi'
        ));
    }

    /**
     * 📤 XUẤT BÁO CÁO EXCEL
     */
    public function export(Request $request)
    {
        $user = Auth::user();
        $phongBanId = $this->getPhongBanId($user);

        if (!$phongBanId) {
            return redirect()->back()->with('error', 'Bạn chưa được phân công phòng ban.');
        }

        $phongBan = PhongBan::find($phongBanId);
        $nhanVienIds = $this->getNhanVienIds($phongBanId);
        $loai = $request->input('loai', 'attendance');
        $thang = $request->input('thang', Carbon::now()->month);
        $nam = $request->input('nam', Carbon::now()->year);

        // ========== XUẤT BÁO CÁO CHẤM CÔNG ==========
        if ($loai == 'attendance') {
            $nhanViens = NguoiDung::with(['hoSo', 'chucVu'])
                ->whereIn('id', $nhanVienIds)
                ->get()
                ->map(function ($nv) use ($thang, $nam) {
                    return [
                        'ma_nhan_vien' => $nv->hoSo->ma_nhan_vien ?? 'N/A',
                        'ho_ten' => ($nv->hoSo->ho ?? '') . ' ' . ($nv->hoSo->ten ?? ''),
                        'chuc_vu' => $nv->chucVu->ten ?? 'N/A',
                        'so_ngay_cham_cong' => ChamCong::where('nguoi_dung_id', $nv->id)
                            ->whereMonth('ngay_cham_cong', $thang)
                            ->whereYear('ngay_cham_cong', $nam)
                            ->count(),
                        'so_ngay_di_muon' => ChamCong::where('nguoi_dung_id', $nv->id)
                            ->whereMonth('ngay_cham_cong', $thang)
                            ->whereYear('ngay_cham_cong', $nam)
                            ->where('trang_thai', 'di_muon')
                            ->count(),
                        'so_ngay_ve_som' => ChamCong::where('nguoi_dung_id', $nv->id)
                            ->whereMonth('ngay_cham_cong', $thang)
                            ->whereYear('ngay_cham_cong', $nam)
                            ->where('trang_thai', 've_som')
                            ->count(),
                        'tong_gio_lam' => round(ChamCong::where('nguoi_dung_id', $nv->id)
                            ->whereMonth('ngay_cham_cong', $thang)
                            ->whereYear('ngay_cham_cong', $nam)
                            ->sum('so_gio_lam'), 2),
                    ];
                })
                ->sortByDesc('so_ngay_cham_cong');

            $fileName = 'bao_cao_cham_cong_' . $phongBan->ma_phong_ban . '_' . $thang . '_' . $nam . '.xlsx';

            return Excel::download(
                new AttendanceReportExport($nhanViens, $phongBan, $thang, $nam),
                $fileName
            );
        }

        // ========== XUẤT BÁO CÁO NGHỈ PHÉP ==========
        if ($loai == 'leave') {
            $donNghis = DonXinNghi::with(['nguoiDung.hoSo', 'loaiNghiPhep'])
                ->whereIn('nguoi_dung_id', $nhanVienIds)
                ->whereMonth('ngay_bat_dau', $thang)
                ->whereYear('ngay_bat_dau', $nam)
                ->orderBy('ngay_bat_dau', 'desc')
                ->get();

            $fileName = 'bao_cao_nghi_phep_' . $phongBan->ma_phong_ban . '_' . $thang . '_' . $nam . '.xlsx';

            return Excel::download(
                new LeaveReportExport($donNghis, $phongBan, $thang, $nam),
                $fileName
            );
        }

        // ========== XUẤT BÁO CÁO TỔNG QUAN ==========
        if ($loai == 'overview') {
            $data = [
                'phongBan' => $phongBan,
                'thang' => $thang,
                'nam' => $nam,
                'tongNhanVien' => count($nhanVienIds),
                'nhanVienNam' => NguoiDung::whereIn('id', $nhanVienIds)->whereHas('hoSo', function ($q) {
                    $q->where('gioi_tinh', 'nam');
                })->count(),
                'nhanVienNu' => NguoiDung::whereIn('id', $nhanVienIds)->whereHas('hoSo', function ($q) {
                    $q->where('gioi_tinh', 'nu');
                })->count(),
                'soNgayDiMuon' => ChamCong::whereIn('nguoi_dung_id', $nhanVienIds)
                    ->whereMonth('ngay_cham_cong', $thang)
                    ->whereYear('ngay_cham_cong', $nam)
                    ->where('trang_thai', 'di_muon')
                    ->count(),
                'soNgayVeSom' => ChamCong::whereIn('nguoi_dung_id', $nhanVienIds)
                    ->whereMonth('ngay_cham_cong', $thang)
                    ->whereYear('ngay_cham_cong', $nam)
                    ->where('trang_thai', 've_som')
                    ->count(),
                'soNgayTangCa' => ChamCong::whereIn('nguoi_dung_id', $nhanVienIds)
                    ->whereMonth('ngay_cham_cong', $thang)
                    ->whereYear('ngay_cham_cong', $nam)
                    ->where('trang_thai', 'tang_ca')
                    ->count(),
                'soNgayLam' => ChamCong::whereIn('nguoi_dung_id', $nhanVienIds)
                    ->whereMonth('ngay_cham_cong', $thang)
                    ->whereYear('ngay_cham_cong', $nam)
                    ->count(),
                'tyLeChamCong' => count($nhanVienIds) > 0
                    ? round((ChamCong::whereIn('nguoi_dung_id', $nhanVienIds)
                        ->whereMonth('ngay_cham_cong', $thang)
                        ->whereYear('ngay_cham_cong', $nam)
                        ->count() / (count($nhanVienIds) * Carbon::create($nam, $thang)->daysInMonth)) * 100, 1)
                    : 0,
                'tongDonNghi' => DonXinNghi::whereIn('nguoi_dung_id', $nhanVienIds)
                    ->whereMonth('ngay_bat_dau', $thang)
                    ->whereYear('ngay_bat_dau', $nam)
                    ->count(),
                'donNghiChoDuyet' => DonXinNghi::whereIn('nguoi_dung_id', $nhanVienIds)
                    ->whereMonth('ngay_bat_dau', $thang)
                    ->whereYear('ngay_bat_dau', $nam)
                    ->where('trang_thai', 'cho_duyet')
                    ->count(),
                'donNghiDaDuyet' => DonXinNghi::whereIn('nguoi_dung_id', $nhanVienIds)
                    ->whereMonth('ngay_bat_dau', $thang)
                    ->whereYear('ngay_bat_dau', $nam)
                    ->where('trang_thai', 'da_duyet')
                    ->count(),
                'donNghiTuChoi' => DonXinNghi::whereIn('nguoi_dung_id', $nhanVienIds)
                    ->whereMonth('ngay_bat_dau', $thang)
                    ->whereYear('ngay_bat_dau', $nam)
                    ->where('trang_thai', 'tu_choi')
                    ->count(),
                'tongTangCa' => DangKyTangCa::whereIn('nguoi_dung_id', $nhanVienIds)
                    ->whereMonth('ngay_tang_ca', $thang)
                    ->whereYear('ngay_tang_ca', $nam)
                    ->count(),
                'tangCaChoDuyet' => DangKyTangCa::whereIn('nguoi_dung_id', $nhanVienIds)
                    ->whereMonth('ngay_tang_ca', $thang)
                    ->whereYear('ngay_tang_ca', $nam)
                    ->where('trang_thai', 'cho_duyet')
                    ->count(),
                'tangCaDaDuyet' => DangKyTangCa::whereIn('nguoi_dung_id', $nhanVienIds)
                    ->whereMonth('ngay_tang_ca', $thang)
                    ->whereYear('ngay_tang_ca', $nam)
                    ->where('trang_thai', 'da_duyet')
                    ->count(),
                'tongChinhCong' => YeuCauDieuChinhCong::whereIn('nguoi_dung_id', $nhanVienIds)
                    ->whereMonth('ngay', $thang)
                    ->whereYear('ngay', $nam)
                    ->count(),
                'chinhCongChoDuyet' => YeuCauDieuChinhCong::whereIn('nguoi_dung_id', $nhanVienIds)
                    ->whereMonth('ngay', $thang)
                    ->whereYear('ngay', $nam)
                    ->where('trang_thai', 'cho_duyet')
                    ->count(),
            ];

            $fileName = 'bao_cao_tong_quan_' . $phongBan->ma_phong_ban . '_' . $thang . '_' . $nam . '.xlsx';

            return Excel::download(new OverviewReportExport($data), $fileName);
        }

        return redirect()->back()->with('error', 'Loại báo cáo không hợp lệ.');
    }
}
