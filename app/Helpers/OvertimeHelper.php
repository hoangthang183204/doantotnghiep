<?php
// app/Helpers/OvertimeHelper.php

namespace App\Helpers;

use App\Models\DangKyTangCa;
use App\Models\LuongNhanVien;
use App\Models\NguoiDung;
use App\Models\HopDongLaoDong;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OvertimeHelper
{
    // ⭐ CẤU HÌNH GIỜ LÀM HÀNH CHÍNH
    public static $gioLamHanhChinhBatDau = '08:30';
    public static $gioLamHanhChinhKetThuc = '17:30';

    // ═══════════════════════════════════════════════════════════════
    // ⭐⭐⭐ PHẦN TÍNH LƯƠNG TĂNG CA ⭐⭐⭐
    // ═══════════════════════════════════════════════════════════════

    /**
     * ⭐ LẤY LƯƠNG GROSS (Lương tháng thực trả cố định)
     * Ưu tiên: Hợp đồng lao động → Hồ sơ nhân viên → Bảng lương
     */
    public static function getLuongGross($userId)
    {
        // 1. Tìm hợp đồng lao động đang hiệu lực
        $hopDong = HopDongLaoDong::where('nguoi_dung_id', $userId)
            ->where('trang_thai_hop_dong', 'hieu_luc')
            ->orderBy('id', 'desc')
            ->first();
        
        if ($hopDong && $hopDong->luong_co_ban > 0) {
            return (float) $hopDong->luong_co_ban;
        }
        
        // 2. Lấy từ hồ sơ nhân viên
        $user = NguoiDung::with('hoSo')->find($userId);
        if ($user && $user->hoSo && $user->hoSo->luong_co_ban > 0) {
            return (float) $user->hoSo->luong_co_ban;
        }
        
        // 3. Lấy từ bảng lương tháng gần nhất
        $luongNhanVien = LuongNhanVien::where('nguoi_dung_id', $userId)
            ->orderBy('luong_nam', 'desc')
            ->orderBy('luong_thang', 'desc')
            ->first();
        
        if ($luongNhanVien && $luongNhanVien->luong_co_ban > 0) {
            return (float) $luongNhanVien->luong_co_ban;
        }
        
        // 4. Mặc định 5.000.000đ
        return 5000000;
    }

    /**
     * ⭐ TÍNH SỐ NGÀY CÔNG CHUẨN TRONG THÁNG
     * (Tính theo lịch: Thứ 2 - Thứ 6, trừ Thứ 7 và Chủ Nhật)
     */
    public static function tinhSoNgayCongChuan($thang, $nam)
    {
        $ngayDauThang = Carbon::create($nam, $thang, 1);
        $ngayCuoiThang = Carbon::create($nam, $thang, $ngayDauThang->daysInMonth);
        
        $soNgayLam = 0;
        $current = $ngayDauThang->copy();
        
        while ($current->lte($ngayCuoiThang)) {
            // Thứ 7 (6) và Chủ Nhật (0) là ngày nghỉ
            if ($current->dayOfWeek !== 6 && $current->dayOfWeek !== 0) {
                $soNgayLam++;
            }
            $current->addDay();
        }
        
        return $soNgayLam;
    }

    /**
     * ⭐ BƯỚC 1: TÍNH LƯƠNG 1 GIỜ LÀM VIỆC BÌNH THƯỜNG
     * Công thức: Lương Gross / (Số ngày công chuẩn của tháng × 8 giờ)
     */
    public static function tinhLuongMotGio($userId, $thang, $nam)
    {
        // 1. Lấy lương Gross
        $luongGross = self::getLuongGross($userId);
        
        // 2. Lấy số ngày công chuẩn của tháng
        $soNgayCongChuan = self::tinhSoNgayCongChuan($thang, $nam);
        
        // 3. Tính tổng số giờ làm việc bình thường trong tháng
        $tongGioLam = $soNgayCongChuan * 8;
        
        // 4. Tính lương 1 giờ
        if ($tongGioLam <= 0) {
            return 0;
        }
        
        return round($luongGross / $tongGioLam, 0);
    }

    /**
     * ⭐ LẤY HỆ SỐ TĂNG CA
     * - Ngày thường: 1.5 (150%)
     * - Ngày cuối tuần (nghỉ): 2.0 (200%)
     * - Lễ Tết: 4.0 (400%)
     */
    public static function getHeSo($type)
    {
        return match ($type) {
            'ngay_thuong' => 1.5,
            'ngay_nghi' => 2.0,
            'le_tet' => 4.0,
            default => 1.5,
        };
    }

    /**
     * ⭐ BƯỚC 2: TÍNH TIỀN LƯƠNG TĂNG CA
     * Công thức: Lương 1 giờ × Số giờ tăng ca thực tế × Hệ số tăng ca
     */
    public static function tinhLuongTangCa($userId, $hours, $type = 'ngay_thuong', $thang = null, $nam = null)
    {
        if ($thang === null || $nam === null) {
            $now = Carbon::now('Asia/Ho_Chi_Minh');
            $thang = $now->month;
            $nam = $now->year;
        }
        
        // Bước 1: Tính lương 1 giờ
        $luongMotGio = self::tinhLuongMotGio($userId, $thang, $nam);
        
        if ($luongMotGio <= 0) {
            return 0;
        }
        
        // Bước 2: Lấy hệ số tăng ca
        $heSo = self::getHeSo($type);
        
        // Bước 3: Tính tiền lương tăng ca
        $tienTangCa = round($hours * $luongMotGio * $heSo, 0);
        
        return $tienTangCa;
    }

    /**
     * ⭐ TÍNH LƯƠNG TĂNG CA CHI TIẾT (CÓ THÔNG TIN ĐẦY ĐỦ)
     */
    public static function tinhLuongTangCaChiTiet($userId, $hours, $type = 'ngay_thuong', $thang = null, $nam = null)
    {
        if ($thang === null || $nam === null) {
            $now = Carbon::now('Asia/Ho_Chi_Minh');
            $thang = $now->month;
            $nam = $now->year;
        }
        
        // Bước 1: Lấy dữ liệu cơ bản
        $luongGross = self::getLuongGross($userId);
        $soNgayCongChuan = self::tinhSoNgayCongChuan($thang, $nam);
        $tongGioLam = $soNgayCongChuan * 8;
        $luongMotGio = $tongGioLam > 0 ? round($luongGross / $tongGioLam, 0) : 0;
        
        // Bước 2: Lấy hệ số
        $heSoTangCa = self::getHeSo($type);
        
        // Bước 3: Tính tiền
        $tienTangCa = round($hours * $luongMotGio * $heSoTangCa, 0);
        
        // Chi tiết
        $chiTiet = match ($type) {
            'ngay_thuong' => number_format($luongMotGio) . "đ × " . number_format($hours, 1, ',', '') . "h × 1.5 = " . number_format($tienTangCa) . "đ",
            'ngay_nghi' => number_format($luongMotGio) . "đ × " . number_format($hours, 1, ',', '') . "h × 2.0 = " . number_format($tienTangCa) . "đ",
            'le_tet' => number_format($luongMotGio) . "đ × " . number_format($hours, 1, ',', '') . "h × 4.0 = " . number_format($tienTangCa) . "đ",
            default => number_format($luongMotGio) . "đ × " . number_format($hours, 1, ',', '') . "h × 1.5 = " . number_format($tienTangCa) . "đ",
        };

        return [
            'hourly_rate' => $luongMotGio,
            'he_so_tang_ca' => $heSoTangCa,
            'tien_tang_ca' => $tienTangCa,
            'chi_tiet' => $chiTiet,
            'luong_gross' => $luongGross,
            'so_ngay_cong_chuan' => $soNgayCongChuan,
            'tong_gio_lam_trong_thang' => $tongGioLam,
        ];
    }

    /**
     * ⭐ LẤY TÊN HIỂN THỊ CHI TIẾT
     */
    public static function getLoaiLabel($type)
    {
        return match ($type) {
            'ngay_thuong' => 'Ngày thường (150%)',
            'ngay_nghi' => 'Ngày cuối tuần (200%)',
            'le_tet' => 'Lễ, Tết (400%)',
            default => 'Ngày thường (150%)',
        };
    }

    /**
     * ⭐ KIỂM TRA GIỜ TĂNG CA HỢP LỆ
     */
    public static function kiemTraGioTangCaHopLe($gioBatDau, $gioKetThuc, $isWeekend = false)
    {
        $start = Carbon::parse($gioBatDau);
        $end = Carbon::parse($gioKetThuc);
        
        $hours = $start->diffInHours($end);
        
        if ($hours > 8) {
            return [
                'valid' => false,
                'message' => "❌ Thời gian tăng ca tối đa 8 giờ/ngày. Hiện tại: {$hours} giờ"
            ];
        }
        
        if ($hours < 0.5) {
            return [
                'valid' => false,
                'message' => "❌ Thời gian tăng ca tối thiểu 0.5 giờ. Hiện tại: {$hours} giờ"
            ];
        }
        
        // Nếu là ngày cuối tuần, không cần kiểm tra giờ hành chính
        if ($isWeekend) {
            return ['valid' => true, 'message' => '✅ Giờ tăng ca hợp lệ (ngày cuối tuần)'];
        }
        
        // Kiểm tra giờ hành chính cho ngày thường
        $gioHanhChinhKT = self::$gioLamHanhChinhKetThuc;
        $startTime = $start->format('H:i');
        $endTime = $end->format('H:i');
        
        if ($startTime < $gioHanhChinhKT) {
            return [
                'valid' => false,
                'message' => "❌ Giờ tăng ca phải bắt đầu sau giờ làm hành chính ({$gioHanhChinhKT}). Hiện tại: {$startTime}"
            ];
        }
        
        return ['valid' => true, 'message' => '✅ Giờ tăng ca hợp lệ'];
    }

    /**
     * ⭐ KIỂM TRA GIỚI HẠN GIỜ TĂNG CA
     */
    public static function kiemTraGioiHan($userId, $ngayTangCa, $soGioTangCa, $excludeId = null)
    {
        $maxHoursPerDay = 8;
        $maxHoursPerMonth = 40;
        $maxHoursPerYear = 200;
        $maxTotalHoursPerDay = 12;
        
        $ngay = Carbon::parse($ngayTangCa);
        
        if ($soGioTangCa > $maxHoursPerDay) {
            return [
                'valid' => false,
                'message' => "❌ Số giờ tăng ca không được vượt quá {$maxHoursPerDay} giờ/ngày.",
                'details' => ['limit' => 'day', 'max' => $maxHoursPerDay, 'current' => $soGioTangCa]
            ];
        }
        
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
                'message' => "❌ Tổng giờ làm việc trong ngày không được vượt quá {$maxTotalHoursPerDay} giờ.",
                'details' => ['limit' => 'total_day', 'max' => $maxTotalHoursPerDay, 'current' => $tongGioLamTrongNgay]
            ];
        }
        
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
                'message' => "❌ Tổng số giờ tăng ca trong tháng đã vượt quá {$maxHoursPerMonth} giờ.",
                'details' => ['limit' => 'month', 'max' => $maxHoursPerMonth, 'current' => $tongGioThangMoi, 'used' => $tongGioThang]
            ];
        }
        
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
                'message' => "❌ Tổng số giờ tăng ca trong năm đã vượt quá {$maxHoursPerYear} giờ.",
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
        $today = Carbon::today('Asia/Ho_Chi_Minh');
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
            ->whereDate('ngay_tang_ca', Carbon::today('Asia/Ho_Chi_Minh'))
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
            ->whereDate('ngay_tang_ca', Carbon::today('Asia/Ho_Chi_Minh'))
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

        $now = Carbon::now('Asia/Ho_Chi_Minh');
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

        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $end = Carbon::parse($overtime->gio_ket_thuc);

        if ($now->gt($end)) return 0;
        return $now->diffInMinutes($end);
    }

    /**
     * ⭐ KIỂM TRA CÓ THỂ XÁC NHẬN HOÀN THÀNH TĂNG CA KHÔNG
     */
    public static function canCompleteOvertime($overtimeId, $currentTime = null)
    {
        $overtime = DangKyTangCa::find($overtimeId);
        if (!$overtime) {
            return ['valid' => false, 'message' => '❌ Không tìm thấy đơn tăng ca'];
        }

        if ($overtime->trang_thai !== 'da_duyet') {
            return ['valid' => false, 'message' => '❌ Đơn tăng ca chưa được duyệt hoặc đã bị từ chối'];
        }

        if ($overtime->da_hoan_thanh) {
            return ['valid' => false, 'message' => '❌ Đơn tăng ca đã được hoàn thành trước đó'];
        }

        $today = Carbon::today('Asia/Ho_Chi_Minh')->format('Y-m-d');
        $ngayTangCa = $overtime->ngay_tang_ca->format('Y-m-d');
        
        if ($ngayTangCa !== $today) {
            return ['valid' => false, 'message' => "❌ Đơn tăng ca không phải hôm nay (Ngày: {$ngayTangCa})"];
        }

        $now = $currentTime ? Carbon::parse($currentTime) : Carbon::now('Asia/Ho_Chi_Minh');
        $gioKetThuc = Carbon::parse($overtime->gio_ket_thuc);
        
        $earlyMinutes = 30;
        $checkoutTime = $gioKetThuc->copy()->subMinutes($earlyMinutes);
        
        if ($now->lt($checkoutTime)) {
            $remainingMinutes = $now->diffInMinutes($gioKetThuc);
            $hours = floor($remainingMinutes / 60);
            $minutes = $remainingMinutes % 60;
            $timeText = $hours > 0 ? "còn {$hours} giờ {$minutes} phút" : "còn {$minutes} phút";
            
            return [
                'valid' => false,
                'message' => "❌ Chưa đến giờ kết thúc tăng ca ({$overtime->gio_ket_thuc}), {$timeText} nữa."
            ];
        }

        return [
            'valid' => true,
            'message' => '✅ Có thể xác nhận hoàn thành tăng ca',
            'so_gio_thuc_te' => round($overtime->so_gio_tang_ca, 1),
            'thoi_diem_xac_nhan' => $now->format('H:i:s')
        ];
    }

    /**
     * ⭐ LẤY THÔNG TIN THỜI GIAN TĂNG CA
     */
    public static function getOvertimeTimeStatus($overtimeId)
    {
        $overtime = DangKyTangCa::find($overtimeId);
        if (!$overtime) {
            return null;
        }

        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $start = Carbon::parse($overtime->gio_bat_dau);
        $end = Carbon::parse($overtime->gio_ket_thuc);
        
        $status = 'chua_bat_dau';
        $message = 'Chưa đến giờ tăng ca';
        $remainingMinutes = 0;
        
        if ($now->gte($start) && $now->lte($end)) {
            $status = 'dang_dien_ra';
            $remainingMinutes = $now->diffInMinutes($end);
            $message = "Đang diễn ra, còn " . self::formatHours($remainingMinutes / 60);
        } elseif ($now->gt($end)) {
            $status = 'da_ket_thuc';
            $message = 'Đã kết thúc';
            $remainingMinutes = 0;
        } else {
            $remainingMinutes = $now->diffInMinutes($start);
            $message = "Chưa bắt đầu, còn " . self::formatHours($remainingMinutes / 60);
        }
        
        return [
            'status' => $status,
            'message' => $message,
            'start_time' => $start->format('H:i'),
            'end_time' => $end->format('H:i'),
            'now' => $now->format('H:i:s'),
            'remaining_minutes' => $remainingMinutes,
            'remaining_text' => self::formatHours($remainingMinutes / 60),
            'can_start' => $now->gte($start->copy()->subMinutes(30)),
            'can_complete' => $now->gte($end->copy()->subMinutes(30)),
        ];
    }
}