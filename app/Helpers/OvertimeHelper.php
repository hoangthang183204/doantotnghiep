<?php
// app/Helpers/OvertimeHelper.php

namespace App\Helpers;

use App\Models\DangKyTangCa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OvertimeHelper
{
    /**
     * Lấy lương theo giờ của nhân viên
     */
    public static function getHourlyRate($userId, $default = 0)
    {
        $user = \App\Models\NguoiDung::find($userId);
        if (!$user) {
            return $default;
        }

        // Lấy lương cơ bản từ helper SalaryHelper
        $luongCoBan = SalaryHelper::getBaseSalary($userId);
        if ($luongCoBan > 0) {
            // Tính lương theo giờ: lương cơ bản / (26 ngày * 8 giờ)
            return round($luongCoBan / (26 * 8), 0);
        }

        return $default;
    }

    /**
     * ⭐ LẤY HỆ SỐ TĂNG CA
     */
    public static function getHeSo($type)
    {
        return match ($type) {
            'ngay_thuong' => 1.5,   // 150% tiền tăng ca trả thêm
            'ngay_nghi' => 2.0,     // 200% tiền tăng ca trả thêm
            'le_tet' => 3.0,        // 300% tiền tăng ca trả thêm
            default => 1.5,
        };
    }

    /**
     * ⭐ LẤY HỆ SỐ LƯƠNG GỐC
     */
    public static function getHeSoLuongGoc($type)
    {
        return match ($type) {
            'ngay_thuong' => 1.0,   // 100% lương ngày thường
            'ngay_nghi' => 0.0,     // 0% ngày nghỉ không lương
            'le_tet' => 1.0,        // 100% ngày nghỉ có lương
            default => 1.0,
        };
    }

    /**
     * ⭐ LẤY TỔNG HỆ SỐ (Lương gốc + Tiền tăng ca trả thêm)
     */
    public static function getTongHeSo($type)
    {
        return match ($type) {
            'ngay_thuong' => 2.5,   // 100% + 150% = 250%
            'ngay_nghi' => 2.0,     // 0% + 200% = 200%
            'le_tet' => 4.0,        // 100% + 300% = 400%
            default => 2.5,
        };
    }

    /**
     * ⭐ LẤY TÊN HIỂN THỊ CHI TIẾT
     */
    public static function getLoaiLabel($type)
    {
        return match ($type) {
            'ngay_thuong' => 'Ngày thường (150% - Tổng 250%)',
            'ngay_nghi' => 'Ngày nghỉ (200% - Tổng 200%)',
            'le_tet' => 'Lễ, Tết (300% - Tổng 400%)',
            default => 'Ngày thường (150% - Tổng 250%)',
        };
    }

    /**
     * ⭐ TÍNH LƯƠNG TĂNG CA CHI TIẾT
     */
    public static function tinhLuongTangCaChiTiet($userId, $hours, $type = 'ngay_thuong')
    {
        $hourlyRate = self::getHourlyRate($userId);
        if ($hourlyRate <= 0) {
            return [
                'hourly_rate' => 0,
                'he_so_tang_ca' => 0,
                'he_so_luong_goc' => 0,
                'tong_he_so' => 0,
                'tien_tang_ca' => 0,
                'luong_goc' => 0,
                'tong_thu_nhap' => 0,
                'chi_tiet' => 'Không có dữ liệu lương'
            ];
        }

        $heSoTangCa = self::getHeSo($type);
        $heSoLuongGoc = self::getHeSoLuongGoc($type);
        $tongHeSo = self::getTongHeSo($type);

        $tienTangCa = round($hours * $hourlyRate * $heSoTangCa, 0);
        $luongGoc = round($hours * $hourlyRate * $heSoLuongGoc, 0);
        $tongThuNhap = round($hours * $hourlyRate * $tongHeSo, 0);

        $chiTiet = match ($type) {
            'ngay_thuong' => "Lương gốc: " . number_format($luongGoc) . "đ + Tăng ca 150%: " . number_format($tienTangCa) . "đ = " . number_format($tongThuNhap) . "đ (250%)",
            'ngay_nghi' => "Lương gốc: 0đ (ngày nghỉ không lương) + Tăng ca 200%: " . number_format($tienTangCa) . "đ = " . number_format($tongThuNhap) . "đ (200%)",
            'le_tet' => "Lương gốc: " . number_format($luongGoc) . "đ (ngày lễ có lương) + Tăng ca 300%: " . number_format($tienTangCa) . "đ = " . number_format($tongThuNhap) . "đ (400%)",
            default => "Lương gốc: " . number_format($luongGoc) . "đ + Tăng ca: " . number_format($tienTangCa) . "đ = " . number_format($tongThuNhap) . "đ",
        };

        return [
            'hourly_rate' => $hourlyRate,
            'he_so_tang_ca' => $heSoTangCa,
            'he_so_luong_goc' => $heSoLuongGoc,
            'tong_he_so' => $tongHeSo,
            'tien_tang_ca' => $tienTangCa,
            'luong_goc' => $luongGoc,
            'tong_thu_nhap' => $tongThuNhap,
            'chi_tiet' => $chiTiet,
        ];
    }

    /**
     * ⭐ TÍNH LƯƠNG TĂNG CA (CŨ - GIỮ ĐỂ TƯƠNG THÍCH)
     */
    public static function tinhLuongTangCa($userId, $hours, $type = 'ngay_thuong')
    {
        $result = self::tinhLuongTangCaChiTiet($userId, $hours, $type);
        return $result['tien_tang_ca'];
    }

    /**
     * ⭐ KIỂM TRA GIỚI HẠN GIỜ TĂNG CA THEO QUY ĐỊNH
     */
    public static function kiemTraGioiHan($userId, $ngayTangCa, $soGioTangCa, $excludeId = null)
    {
        // Cấu hình giới hạn
        $maxHoursPerDay = 4;      // Tối đa 4 giờ/ngày (50% của 8h)
        $maxHoursPerMonth = 40;   // Tối đa 40 giờ/tháng
        $maxHoursPerYear = 200;   // Tối đa 200 giờ/năm
        $maxTotalHoursPerDay = 12; // Tổng giờ làm việc tối đa 12h/ngày
        
        $ngay = Carbon::parse($ngayTangCa);
        
        // 1️⃣ KIỂM TRA GIỚI HẠN NGÀY
        if ($soGioTangCa > $maxHoursPerDay) {
            return [
                'valid' => false,
                'message' => "❌ Số giờ tăng ca không được vượt quá {$maxHoursPerDay} giờ/ngày (tối đa 50% giờ làm việc bình thường).",
                'details' => ['limit' => 'day', 'max' => $maxHoursPerDay, 'current' => $soGioTangCa]
            ];
        }
        
        // 2️⃣ KIỂM TRA TỔNG GIỜ LÀM VIỆC TRONG NGÀY
        $tongGioTrongNgay = DangKyTangCa::where('nguoi_dung_id', $userId)
            ->where('ngay_tang_ca', $ngayTangCa)
            ->whereIn('trang_thai', ['da_duyet', 'cho_duyet'])
            ->when($excludeId, function ($query) use ($excludeId) {
                return $query->where('id', '!=', $excludeId);
            })
            ->sum('so_gio_tang_ca');
        
        $gioLamChinh = 8;
        $tongGioLamTrongNgay = $gioLamChinh + $tongGioTrongNgay + $soGioTangCa;
        if ($tongGioLamTrongNgay > $maxTotalHoursPerDay) {
            return [
                'valid' => false,
                'message' => "❌ Tổng giờ làm việc trong ngày không được vượt quá {$maxTotalHoursPerDay} giờ. (Giờ làm chính: {$gioLamChinh}h, Tăng ca hiện tại: {$tongGioTrongNgay}h, Đề xuất: {$soGioTangCa}h)",
                'details' => ['limit' => 'total_day', 'max' => $maxTotalHoursPerDay, 'current' => $tongGioLamTrongNgay]
            ];
        }
        
        // 3️⃣ KIỂM TRA GIỚI HẠN THÁNG
        $thangHienTai = $ngay->format('Y-m');
        $tongGioThang = DangKyTangCa::where('nguoi_dung_id', $userId)
            ->whereIn('trang_thai', ['da_duyet', 'cho_duyet'])
            ->whereRaw("DATE_FORMAT(ngay_tang_ca, '%Y-%m') = ?", [$thangHienTai])
            ->when($excludeId, function ($query) use ($excludeId) {
                return $query->where('id', '!=', $excludeId);
            })
            ->sum('so_gio_tang_ca');
        
        $tongGioThangMoi = $tongGioThang + $soGioTangCa;
        if ($tongGioThangMoi > $maxHoursPerMonth) {
            return [
                'valid' => false,
                'message' => "❌ Tổng số giờ tăng ca trong tháng đã vượt quá {$maxHoursPerMonth} giờ. (Đã đăng ký: {$tongGioThang} giờ, Đề xuất: {$soGioTangCa} giờ)",
                'details' => ['limit' => 'month', 'max' => $maxHoursPerMonth, 'current' => $tongGioThangMoi, 'used' => $tongGioThang]
            ];
        }
        
        // 4️⃣ KIỂM TRA GIỚI HẠN NĂM
        $namHienTai = $ngay->format('Y');
        $tongGioNam = DangKyTangCa::where('nguoi_dung_id', $userId)
            ->whereIn('trang_thai', ['da_duyet', 'cho_duyet'])
            ->whereYear('ngay_tang_ca', $namHienTai)
            ->when($excludeId, function ($query) use ($excludeId) {
                return $query->where('id', '!=', $excludeId);
            })
            ->sum('so_gio_tang_ca');
        
        $tongGioNamMoi = $tongGioNam + $soGioTangCa;
        if ($tongGioNamMoi > $maxHoursPerYear) {
            return [
                'valid' => false,
                'message' => "❌ Tổng số giờ tăng ca trong năm đã vượt quá {$maxHoursPerYear} giờ. (Đã đăng ký: {$tongGioNam} giờ, Đề xuất: {$soGioTangCa} giờ)",
                'details' => ['limit' => 'year', 'max' => $maxHoursPerYear, 'current' => $tongGioNamMoi, 'used' => $tongGioNam]
            ];
        }
        
        return [
            'valid' => true,
            'message' => 'Đơn tăng ca hợp lệ',
            'details' => [
                'day_limit' => $maxHoursPerDay,
                'month_limit' => $maxHoursPerMonth,
                'year_limit' => $maxHoursPerYear,
                'day_used' => $tongGioTrongNgay,
                'month_used' => $tongGioThang,
                'year_used' => $tongGioNam,
                'proposed_hours' => $soGioTangCa
            ]
        ];
    }

    /**
     * Format giờ thành dạng dễ đọc
     */
    public static function formatHours($hours)
    {
        if ($hours == 0) {
            return '0h';
        }
        
        $gio = floor($hours);
        $phut = round(($hours - $gio) * 60);
        
        if ($gio == 0) {
            return "{$phut} phút";
        }
        
        if ($phut == 0) {
            return "{$gio} giờ";
        }
        
        return "{$gio} giờ {$phut} phút";
    }

    /**
     * ⭐ THỐNG KÊ GIỜ TĂNG CA
     */
    public static function thongKeGioTangCa($userId)
    {
        $today = Carbon::today();
        $month = $today->format('Y-m');
        $year = $today->format('Y');
        $statuses = ['da_duyet', 'cho_duyet'];
        
        $maxHoursPerMonth = 40;
        $maxHoursPerYear = 200;
        
        $trongThang = DangKyTangCa::where('nguoi_dung_id', $userId)
            ->whereRaw("DATE_FORMAT(ngay_tang_ca, '%Y-%m') = ?", [$month])
            ->whereIn('trang_thai', $statuses)
            ->sum('so_gio_tang_ca');
        
        $trongNam = DangKyTangCa::where('nguoi_dung_id', $userId)
            ->whereYear('ngay_tang_ca', $year)
            ->whereIn('trang_thai', $statuses)
            ->sum('so_gio_tang_ca');
        
        $choDuyet = DangKyTangCa::where('nguoi_dung_id', $userId)
            ->where('trang_thai', 'cho_duyet')
            ->sum('so_gio_tang_ca');
        
        $hoanThanh = DangKyTangCa::where('nguoi_dung_id', $userId)
            ->where('trang_thai', 'da_duyet')
            ->where('da_hoan_thanh', true)
            ->sum('so_gio_tang_ca');
        
        return [
            'trong_thang' => round($trongThang, 1),
            'trong_nam' => round($trongNam, 1),
            'cho_duyet' => round($choDuyet, 1),
            'hoan_thanh' => round($hoanThanh, 1),
            'limit_month' => $maxHoursPerMonth,
            'limit_year' => $maxHoursPerYear,
            'remaining_month' => max(0, $maxHoursPerMonth - $trongThang),
            'remaining_year' => max(0, $maxHoursPerYear - $trongNam),
            'trong_thang_text' => self::formatHours($trongThang),
            'trong_nam_text' => self::formatHours($trongNam),
            'remaining_month_text' => self::formatHours(max(0, $maxHoursPerMonth - $trongThang)),
            'remaining_year_text' => self::formatHours(max(0, $maxHoursPerYear - $trongNam)),
            'limit_month_text' => self::formatHours($maxHoursPerMonth),
            'limit_year_text' => self::formatHours($maxHoursPerYear),
        ];
    }

    /**
     * ⭐ KIỂM TRA NHÂN VIÊN CÓ ĐƠN TĂNG CA ĐANG HOẠT ĐỘNG HÔM NAY KHÔNG
     */
    public static function hasActiveOvertime($userId)
    {
        return DangKyTangCa::where('nguoi_dung_id', $userId)
            ->whereDate('ngay_tang_ca', Carbon::today())
            ->where('trang_thai', 'da_duyet')
            ->where('da_hoan_thanh', false)
            ->exists();
    }

    /**
     * ⭐ LẤY ĐƠN TĂNG CA ĐANG HOẠT ĐỘNG HÔM NAY
     */
    public static function getActiveOvertime($userId)
    {
        return DangKyTangCa::where('nguoi_dung_id', $userId)
            ->whereDate('ngay_tang_ca', Carbon::today())
            ->where('trang_thai', 'da_duyet')
            ->where('da_hoan_thanh', false)
            ->first();
    }

    /**
     * ⭐ KIỂM TRA NHÂN VIÊN ĐANG TRONG GIỜ TĂNG CA
     */
    public static function isInOvertimePeriod($userId)
    {
        $overtime = self::getActiveOvertime($userId);
        if (!$overtime) return false;

        $now = Carbon::now();
        $start = Carbon::parse($overtime->gio_bat_dau);
        $end = Carbon::parse($overtime->gio_ket_thuc);

        return $now->between($start, $end);
    }

    /**
     * ⭐ LẤY SỐ PHÚT TĂNG CA CÒN LẠI
     */
    public static function getRemainingOvertimeMinutes($userId)
    {
        $overtime = self::getActiveOvertime($userId);
        if (!$overtime) return 0;

        $now = Carbon::now();
        $end = Carbon::parse($overtime->gio_ket_thuc);

        if ($now->gt($end)) return 0;
        return $now->diffInMinutes($end);
    }
}