<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use App\Models\ChamCong;
use App\Models\DonXinNghi;
use App\Models\HoSoNguoiDung;
use App\Models\PhongBan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ==================== THỐNG KÊ CƠ BẢN ====================

        // Tổng nhân viên đang làm việc
        $tongNguoiDung = NguoiDung::where('trang_thai', 1)->count();

        // Nhân viên mới trong tháng
        $nhanVienMoi = HoSoNguoiDung::where('created_at', '>=', Carbon::now()->subDays(30))->count();

        // Tỷ lệ so với 30 ngày trước đó
        $previousPeriod = HoSoNguoiDung::whereBetween('created_at', [
            Carbon::now()->subDays(60),
            Carbon::now()->subDays(30)
        ])->count();
        $tyLeNhanVienMoiThayDoi = $previousPeriod > 0
            ? round((($nhanVienMoi - $previousPeriod) / $previousPeriod) * 100, 1)
            : ($nhanVienMoi > 0 ? 100 : 0);

        // Chấm công hôm nay
        $nhanVienChamCongHomNay = ChamCong::whereDate('ngay_cham_cong', Carbon::today())->count();

        // Tỷ lệ chấm công hôm nay so với hôm qua
        $homQua = ChamCong::whereDate('ngay_cham_cong', Carbon::yesterday())->count();
        $tyLeChamCongThayDoi = $homQua > 0 ? (($nhanVienChamCongHomNay - $homQua) / $homQua) * 100 : 0;

        // Nghỉ phép hôm nay
        $nhanVienNghiPhepHomNay = DonXinNghi::where('trang_thai', 'da_duyet')
            ->whereDate('ngay_bat_dau', '<=', Carbon::today())
            ->whereDate('ngay_ket_thuc', '>=', Carbon::today())
            ->count();

        // Tỷ lệ nghỉ phép so với hôm qua
        $homQuaNghi = DonXinNghi::where('trang_thai', 'da_duyet')
            ->whereDate('ngay_bat_dau', '<=', Carbon::yesterday())
            ->whereDate('ngay_ket_thuc', '>=', Carbon::yesterday())
            ->count();
        $tyLeNghiPhepThayDoi = $homQuaNghi > 0 ? (($nhanVienNghiPhepHomNay - $homQuaNghi) / $homQuaNghi) * 100 : 0;

        // ❌ ĐÃ XÓA PHẦN ỨNG VIÊN

        // ==================== DỮ LIỆU CHO BIỂU ĐỒ ====================

        // Tỷ lệ chấm công theo tháng
        $dataAverageAttendanceRate = [];
        for ($month = 1; $month <= 12; $month++) {
            $totalEmployees = NguoiDung::where('trang_thai', 1)->count();
            $attendedDays = ChamCong::whereMonth('ngay_cham_cong', $month)
                ->whereYear('ngay_cham_cong', Carbon::now()->year)
                ->count();

            if ($totalEmployees > 0) {
                $totalWorkingDays = Carbon::create(Carbon::now()->year, $month)->daysInMonth;
                $maxAttendance = $totalEmployees * $totalWorkingDays;
                $rate = $maxAttendance > 0 ? round(($attendedDays / $maxAttendance) * 100, 1) : 0;
            } else {
                $rate = 0;
            }
            $dataAverageAttendanceRate[] = $rate;
        }

        // Số lượng nhân viên theo phòng ban
        $phongBans = PhongBan::all();
        $DesignationName = [];
        $designationSeries = [];

        foreach ($phongBans as $pb) {
            $DesignationName[] = $pb->ten_phong_ban;
            $designationSeries[] = NguoiDung::where('phong_ban_id', $pb->id)->where('trang_thai', 1)->count();
        }

        // Thống kê giới tính
        $nam = HoSoNguoiDung::where('gioi_tinh', 'nam')->count();
        $nu = HoSoNguoiDung::where('gioi_tinh', 'nu')->count();
        $khac = HoSoNguoiDung::whereNotIn('gioi_tinh', ['nam', 'nu'])->count();
        $tong = $nam + $nu + $khac;

        $labelsGender = ['Nam', 'Nữ', 'Khác'];
        $dataGender = [];
        if ($tong > 0) {
            $dataGender[] = round(($nam / $tong) * 100, 1);
            $dataGender[] = round(($nu / $tong) * 100, 1);
            $dataGender[] = round(($khac / $tong) * 100, 1);
        } else {
            $dataGender = [0, 0, 0];
        }

        // Dữ liệu nghỉ phép
        $sickLeaveData = [];
        $casualLeaveData = [];
        $currentYear = Carbon::now()->year;

        for ($month = 1; $month <= 12; $month++) {
            $weeklySick = [];
            $weeklyCasual = [];

            for ($week = 1; $week <= 5; $week++) {
                $startDate = Carbon::create($currentYear, $month, 1)->addWeeks($week - 1);
                $endDate = $startDate->copy()->addDays(6);

                if ($startDate->month > $month) break;
                if ($endDate->month < $month) $endDate = Carbon::create($currentYear, $month)->endOfMonth();

                $totalLeave = DonXinNghi::where('trang_thai', 'da_duyet')
                    ->whereBetween('ngay_bat_dau', [$startDate, $endDate])
                    ->count();

                $weeklySick[] = $totalLeave;
                $weeklyCasual[] = 0;
            }

            while (count($weeklySick) < 5) {
                $weeklySick[] = 0;
                $weeklyCasual[] = 0;
            }

            $sickLeaveData[] = $weeklySick;
            $casualLeaveData[] = $weeklyCasual;
        }

        // Nhân viên mới nhất
        $employees = HoSoNguoiDung::with('nguoiDung')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'tongNguoiDung',
            'nhanVienMoi',
            'tyLeNhanVienMoiThayDoi',
            'nhanVienChamCongHomNay',
            'tyLeChamCongThayDoi',
            'nhanVienNghiPhepHomNay',
            'tyLeNghiPhepThayDoi',
            'dataAverageAttendanceRate',
            'DesignationName',
            'designationSeries',
            'labelsGender',
            'dataGender',
            'sickLeaveData',
            'casualLeaveData',
            'employees'
        ));
    }
}