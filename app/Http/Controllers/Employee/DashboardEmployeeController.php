<?php
// app/Http/Controllers/Employee/DashboardController.php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\ChamCong;
use App\Models\DonXinNghi;
use App\Models\NguoiDung;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardEmployeeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $hoSo = $user->hoSo;
        
        // =============================================
        // THÔNG TIN CƠ BẢN
        // =============================================
        $hoTen = $hoSo ? ($hoSo->ho . ' ' . $hoSo->ten) : $user->ten_dang_nhap;
        $email = $user->email;
        $phongBan = $user->phongBan->ten_phong_ban ?? 'Chưa có phòng ban';
        
        // Số ngày làm việc (tính từ ngày tạo)
        $ngayTao = $hoSo ? Carbon::parse($hoSo->created_at) : Carbon::parse($user->created_at);
        $soNgayLamViec = $ngayTao->diffInDays(Carbon::now());

        // =============================================
        // THỐNG KÊ THÁNG NÀY
        // =============================================
        $thangHienTai = Carbon::now()->month;
        $namHienTai = Carbon::now()->year;
        
        // Tổng số ngày trong tháng
        $tongNgayTrongThang = Carbon::now()->daysInMonth;
        
        // Ngày đã chấm công trong tháng
        $ngayChamCong = ChamCong::where('nguoi_dung_id', $user->id)
            ->whereMonth('ngay_cham_cong', $thangHienTai)
            ->whereYear('ngay_cham_cong', $namHienTai)
            ->count();
        
        // Tỷ lệ chấm công
        $tyLeChamCong = $tongNgayTrongThang > 0 
            ? round(($ngayChamCong / $tongNgayTrongThang) * 100) 
            : 0;
        
        $soNgayThieu = $tongNgayTrongThang - $ngayChamCong;
        
        // Số ngày đi trễ trong tháng
        $soNgayDiTre = ChamCong::where('nguoi_dung_id', $user->id)
            ->whereMonth('ngay_cham_cong', $thangHienTai)
            ->whereYear('ngay_cham_cong', $namHienTai)
            ->where('trang_thai', 'di_muon')
            ->count();
        
        // Số ngày về sớm trong tháng
        $soNgayVeSom = ChamCong::where('nguoi_dung_id', $user->id)
            ->whereMonth('ngay_cham_cong', $thangHienTai)
            ->whereYear('ngay_cham_cong', $namHienTai)
            ->where('trang_thai', 've_som')
            ->count();
        
        // Số ngày nghỉ phép trong tháng
        $soNgayNghiPhep = DonXinNghi::where('nguoi_dung_id', $user->id)
            ->where('trang_thai', 'da_duyet')
            ->whereMonth('ngay_bat_dau', $thangHienTai)
            ->whereYear('ngay_bat_dau', $namHienTai)
            ->sum('so_ngay_nghi');

        // =============================================
        // THỐNG KÊ CẢ NĂM 2026
        // =============================================
        $tongNgayChamCongNam = ChamCong::where('nguoi_dung_id', $user->id)
            ->whereYear('ngay_cham_cong', $namHienTai)
            ->count();
        
        $tongDiTreNam = ChamCong::where('nguoi_dung_id', $user->id)
            ->whereYear('ngay_cham_cong', $namHienTai)
            ->where('trang_thai', 'di_muon')
            ->count();
        
        $tongVeSomNam = ChamCong::where('nguoi_dung_id', $user->id)
            ->whereYear('ngay_cham_cong', $namHienTai)
            ->where('trang_thai', 've_som')
            ->count();
        
        $tongNghiPhepNam = DonXinNghi::where('nguoi_dung_id', $user->id)
            ->where('trang_thai', 'da_duyet')
            ->whereYear('ngay_bat_dau', $namHienTai)
            ->sum('so_ngay_nghi');

        // =============================================
        // RANKING TRONG PHÒNG BAN
        // =============================================
        $phongBanId = $user->phong_ban_id;
        
        // Lấy danh sách nhân viên trong phòng và số ngày chấm công
        $rankingList = ChamCong::whereYear('ngay_cham_cong', $namHienTai)
            ->whereHas('nguoi_dung', function($q) use ($phongBanId) {
                $q->where('phong_ban_id', $phongBanId)
                  ->where('trang_thai', 1);
            })
            ->select('nguoi_dung_id', DB::raw('COUNT(*) as tong_cham_cong'))
            ->groupBy('nguoi_dung_id')
            ->orderBy('tong_cham_cong', 'desc')
            ->with('nguoi_dung.hoSo')
            ->get();
        
        $viTri = 0;
        $tongNhanVien = $rankingList->count();
        
        foreach ($rankingList as $key => $nv) {
            if ($nv->nguoi_dung_id == $user->id) {
                $viTri = $key + 1;
                break;
            }
        }
        
        $tyLeTrenPhong = $tongNhanVien > 0 
            ? round((($tongNhanVien - $viTri) / $tongNhanVien) * 100) 
            : 0;

        // =============================================
        // 5 NHÂN VIÊN ĐỨNG ĐẦU PHÒNG BAN
        // =============================================
        $topEmployees = $rankingList->take(5)->map(function($item) {
            $hoSo = $item->nguoi_dung->hoSo;
            return [
                'ho_ten' => $hoSo ? ($hoSo->ho . ' ' . $hoSo->ten) : $item->nguoi_dung->ten_dang_nhap,
                'ma_nhan_vien' => $hoSo ? $hoSo->ma_nhan_vien : null,
                'tong_cham_cong' => $item->tong_cham_cong,
            ];
        });

        // =============================================
        // DỮ LIỆU CHO BIỂU ĐỒ 6 THÁNG
        // =============================================
        $chartLabels = [];
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $chartLabels[] = 'Tháng ' . $month->month;
            $soNgay = ChamCong::where('nguoi_dung_id', $user->id)
                ->whereMonth('ngay_cham_cong', $month->month)
                ->whereYear('ngay_cham_cong', $month->year)
                ->count();
            $chartData[] = $soNgay;
        }

        // ⭐⭐⭐ LOGIC ĐỌC SỐ DƯ PHÉP ĐỘNG TỪ DATABASE ⭐⭐⭐
        $soDuPhep = \App\Models\SoDuPhep::where('nguoi_dung_id', $user->id)
            ->where('nam', $namHienTai)
            ->first();

        // Nếu hệ thống chưa khởi tạo dữ liệu cho nhân viên này, tự tạo bản ghi mẫu bọc lót
        if (!$soDuPhep) {
            $soDuPhep = \App\Models\SoDuPhep::create([
                'nguoi_dung_id' => $user->id,
                'nam' => $namHienTai,
                'phep_nam_moi' => 12.0,
                'phep_cu_chuyen_sang' => 0.0,
                'phep_da_dung' => 0.0
            ]);
        }

        $tongPhepDuocHuong = $soDuPhep->phep_nam_moi + $soDuPhep->phep_cu_chuyen_sang;
        $soDuConLai = max(0, $tongPhepDuocHuong - $soDuPhep->phep_da_dung);

        return view('employee.dashboard', compact(
            'hoTen',
            'email',
            'phongBan',
            'soNgayLamViec',
            'tyLeChamCong',
            'ngayChamCong',
            'tongNgayTrongThang',
            'soNgayThieu',
            'soNgayDiTre',
            'soNgayVeSom',
            'soNgayNghiPhep',
            'tongNgayChamCongNam',
            'tongDiTreNam',
            'tongVeSomNam',
            'tongNghiPhepNam',
            'viTri',
            'tyLeTrenPhong',
            'tongNhanVien',
            'topEmployees',
            'chartLabels',
            'chartData',
            'soDuConLai'
        ));
    }
}