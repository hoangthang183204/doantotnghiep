<?php
// app/Helpers/OvertimeHelper.php

namespace App\Helpers;

use App\Models\DangKyTangCa;
use App\Models\LuongNhanVien;
use App\Models\NguoiDung;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OvertimeHelper
{
    // ⭐ CẤU HÌNH GIỜ LÀM HÀNH CHÍNH
    public static $gioLamHanhChinhBatDau = '08:30';
    public static $gioLamHanhChinhKetThuc = '17:30';

    /**
     * ⭐ KIỂM TRA THỜI GIAN CÓ NẰM TRONG GIỜ HÀNH CHÍNH KHÔNG
     */
    public static function isGioHanhChinh($gioBatDau, $gioKetThuc = null)
    {
        $start = Carbon::parse($gioBatDau);
        $startTime = $start->format('H:i');
        
        $gioBD = self::$gioLamHanhChinhBatDau;
        $gioKT = self::$gioLamHanhChinhKetThuc;
        
        if ($startTime >= $gioBD && $startTime < $gioKT) {
            return true;
        }
        
        if ($gioKetThuc) {
            $end = Carbon::parse($gioKetThuc);
            $endTime = $end->format('H:i');
            
            if ($endTime > $gioBD && $endTime <= $gioKT) {
                return true;
            }
            
            if ($startTime < $gioBD && $endTime > $gioKT) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * ⭐ KIỂM TRA GIỜ TĂNG CA HỢP LỆ (PHẢI SAU GIỜ HÀNH CHÍNH)
     */
    public static function kiemTraGioTangCaHopLe($gioBatDau, $gioKetThuc)
    {
        $start = Carbon::parse($gioBatDau);
        $end = Carbon::parse($gioKetThuc);
        
        $gioHanhChinhBD = self::$gioLamHanhChinhBatDau;
        $gioHanhChinhKT = self::$gioLamHanhChinhKetThuc;
        
        $startTime = $start->format('H:i');
        $endTime = $end->format('H:i');
        
        if ($startTime < $gioHanhChinhKT) {
            return [
                'valid' => false,
                'message' => "❌ Giờ tăng ca phải bắt đầu sau giờ làm hành chính ({$gioHanhChinhKT}). Hiện tại: {$startTime}"
            ];
        }
        
        if ($endTime < $gioHanhChinhKT && $endTime > $gioHanhChinhBD) {
            return [
                'valid' => false,
                'message' => "❌ Giờ tăng ca phải kết thúc sau giờ làm hành chính ({$gioHanhChinhKT}). Hiện tại: {$endTime}"
            ];
        }
        
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
        
        return ['valid' => true, 'message' => '✅ Giờ tăng ca hợp lệ'];
    }

    // ═══════════════════════════════════════════════════════════════
    // ⭐⭐⭐ PHẦN LẤY HỆ SỐ VÀ TÊN HIỂN THỊ ⭐⭐⭐
    // ═══════════════════════════════════════════════════════════════

    /**
     * ⭐ LẤY HỆ SỐ TĂNG CA
     * - Ngày thường: 150%
     * - Ngày cuối tuần (nghỉ): 200%
     * - Lễ Tết: 400% (300% tiền tăng ca + 100% lương ngày lễ)
     */
    public static function getHeSo($type)
    {
        return match ($type) {
            'ngay_thuong' => 1.5,   // 150%
            'ngay_nghi' => 2.0,     // 200%
            'le_tet' => 3.0,        // 300% (chưa tính lương gốc)
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
            'le_tet' => 1.0,        // 100% ngày lễ có lương
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
            'ngay_thuong' => 'Ngày thường (150%)',
            'ngay_nghi' => 'Ngày cuối tuần (200%)',
            'le_tet' => 'Lễ, Tết (400%)',
            default => 'Ngày thường (150%)',
        };
    }

    /**
     * ⭐ LẤY TÊN HIỂN THỊ NGẮN GỌN
     */
    public static function getLoaiLabelShort($type)
    {
        return match ($type) {
            'ngay_thuong' => '150%',
            'ngay_nghi' => '200%',
            'le_tet' => '400%',
            default => '150%',
        };
    }

    /**
     * ⭐ LẤY MÔ TẢ CHI TIẾT
     */
    public static function getLoaiDescription($type)
    {
        return match ($type) {
            'ngay_thuong' => 'Làm thêm ngày thường được hưởng 150% lương giờ',
            'ngay_nghi' => 'Làm thêm ngày cuối tuần được hưởng 200% lương giờ',
            'le_tet' => 'Làm thêm ngày Lễ, Tết được hưởng 400% lương giờ (300% tiền tăng ca + 100% lương ngày lễ)',
            default => 'Làm thêm ngày thường được hưởng 150% lương giờ',
        };
    }

    // ═══════════════════════════════════════════════════════════════
    // ⭐⭐⭐ PHẦN TÍNH LƯƠNG TĂNG CA ⭐⭐⭐
    // ═══════════════════════════════════════════════════════════════

    /**
     * ⭐ LẤY LƯƠNG THỰC TẾ CỦA NHÂN VIÊN TRONG THÁNG
     */
    public static function getLuongThucTeThang($userId, $thang, $nam)
    {
        $luong = LuongNhanVien::where('nguoi_dung_id', $userId)
            ->where('luong_thang', $thang)
            ->where('luong_nam', $nam)
            ->first();
        
        if ($luong) {
            if ($luong->luong_thuc_nhan > 0) {
                return (float) $luong->luong_thuc_nhan;
            }
            if ($luong->luong_theo_cong > 0) {
                return (float) $luong->luong_theo_cong;
            }
            if ($luong->tong_luong > 0) {
                return (float) $luong->tong_luong;
            }
        }

        $lastSalary = LuongNhanVien::where('nguoi_dung_id', $userId)
            ->orderBy('luong_nam', 'desc')
            ->orderBy('luong_thang', 'desc')
            ->first();
        
        if ($lastSalary) {
            if ($lastSalary->luong_thuc_nhan > 0) {
                return (float) $lastSalary->luong_thuc_nhan;
            }
            if ($lastSalary->luong_theo_cong > 0) {
                return (float) $lastSalary->luong_theo_cong;
            }
        }

        $user = NguoiDung::with('hoSo')->find($userId);
        if ($user && $user->hoSo && $user->hoSo->luong_co_ban > 0) {
            return (float) $user->hoSo->luong_co_ban;
        }

        return 5000000;
    }

    /**
     * ⭐ LẤY SỐ NGÀY CÔNG THỰC TẾ TRONG THÁNG
     */
    public static function getSoNgayCongThucTe($userId, $thang, $nam)
    {
        $luong = LuongNhanVien::where('nguoi_dung_id', $userId)
            ->where('luong_thang', $thang)
            ->where('luong_nam', $nam)
            ->first();
        
        if ($luong && $luong->so_ngay_cong > 0) {
            return (float) $luong->so_ngay_cong;
        }
        
        return self::tinhSoNgayLamViec($thang, $nam);
    }

    /**
     * ⭐ TÍNH SỐ NGÀY LÀM VIỆC TRONG THÁNG
     */
    public static function tinhSoNgayLamViec($thang, $nam)
    {
        $ngayDauThang = Carbon::create($nam, $thang, 1);
        $ngayCuoiThang = Carbon::create($nam, $thang, $ngayDauThang->daysInMonth);
        
        $soNgayLam = 0;
        $current = $ngayDauThang->copy();
        
        while ($current->lte($ngayCuoiThang)) {
            if ($current->dayOfWeek !== 6 && $current->dayOfWeek !== 7) {
                $soNgayLam++;
            }
            $current->addDay();
        }
        
        return $soNgayLam;
    }

    /**
     * ⭐ TÍNH TỔNG GIỜ LÀM VIỆC TRONG THÁNG
     */
    public static function tinhTongGioLamTrongThang($userId, $thang, $nam)
    {
        $soNgayCong = self::getSoNgayCongThucTe($userId, $thang, $nam);
        return $soNgayCong * 8;
    }

    /**
     * ⭐ TÍNH LƯƠNG THEO GIỜ CHUẨN
     */
    public static function tinhLuongTheoGio($userId, $thang, $nam)
    {
        $luongThucTe = self::getLuongThucTeThang($userId, $thang, $nam);
        if ($luongThucTe <= 0) return 0;

        $tongGioLam = self::tinhTongGioLamTrongThang($userId, $thang, $nam);
        if ($tongGioLam <= 0) return 0;

        return round($luongThucTe / $tongGioLam, 0);
    }

    /**
     * ⭐ TÍNH LƯƠNG TĂNG CA CHI TIẾT
     */
    public static function tinhLuongTangCaChiTiet($userId, $hours, $type = 'ngay_thuong', $thang = null, $nam = null)
    {
        if ($thang === null || $nam === null) {
            $now = Carbon::now('Asia/Ho_Chi_Minh');
            $thang = $now->month;
            $nam = $now->year;
        }

        $luongThucTe = self::getLuongThucTeThang($userId, $thang, $nam);
        $soNgayCong = self::getSoNgayCongThucTe($userId, $thang, $nam);
        $tongGioLam = $soNgayCong * 8;
        $luongTheoGio = $tongGioLam > 0 ? round($luongThucTe / $tongGioLam, 0) : 0;
        
        if ($luongTheoGio <= 0) {
            return [
                'hourly_rate' => 0,
                'he_so_tang_ca' => 0,
                'he_so_luong_goc' => 0,
                'tong_he_so' => 0,
                'tien_tang_ca' => 0,
                'luong_goc' => 0,
                'tong_thu_nhap' => 0,
                'chi_tiet' => 'Không có dữ liệu lương',
                'luong_thuc_te_thang' => 0,
                'so_ngay_cong' => 0,
                'tong_gio_lam_trong_thang' => 0,
            ];
        }

        $heSoTangCa = self::getHeSo($type);
        $heSoLuongGoc = self::getHeSoLuongGoc($type);
        $tongHeSo = self::getTongHeSo($type);

        $tienTangCa = round($hours * $luongTheoGio * $heSoTangCa, 0);
        $luongGoc = round($hours * $luongTheoGio * $heSoLuongGoc, 0);
        $tongThuNhap = round($hours * $luongTheoGio * $tongHeSo, 0);

        $chiTiet = match ($type) {
            'ngay_thuong' => "Lương gốc: " . number_format($luongGoc) . "đ + Tăng ca 150%: " . number_format($tienTangCa) . "đ = " . number_format($tongThuNhap) . "đ (250%)",
            'ngay_nghi' => "Lương gốc: 0đ (ngày cuối tuần không lương) + Tăng ca 200%: " . number_format($tienTangCa) . "đ = " . number_format($tongThuNhap) . "đ (200%)",
            'le_tet' => "Lương gốc: " . number_format($luongGoc) . "đ (ngày lễ có lương) + Tăng ca 300%: " . number_format($tienTangCa) . "đ = " . number_format($tongThuNhap) . "đ (400%)",
            default => "Lương gốc: " . number_format($luongGoc) . "đ + Tăng ca: " . number_format($tienTangCa) . "đ = " . number_format($tongThuNhap) . "đ",
        };

        return [
            'hourly_rate' => $luongTheoGio,
            'he_so_tang_ca' => $heSoTangCa,
            'he_so_luong_goc' => $heSoLuongGoc,
            'tong_he_so' => $tongHeSo,
            'tien_tang_ca' => $tienTangCa,
            'luong_goc' => $luongGoc,
            'tong_thu_nhap' => $tongThuNhap,
            'chi_tiet' => $chiTiet,
            'luong_thuc_te_thang' => $luongThucTe,
            'so_ngay_cong' => $soNgayCong,
            'tong_gio_lam_trong_thang' => $tongGioLam,
        ];
    }

    /**
     * ⭐ TÍNH LƯƠNG TĂNG CA
     */
    public static function tinhLuongTangCa($userId, $hours, $type = 'ngay_thuong')
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $result = self::tinhLuongTangCaChiTiet($userId, $hours, $type, $now->month, $now->year);
        return $result['tien_tang_ca'];
    }

    /**
     * ⭐ LẤY LƯƠNG THEO GIỜ
     */
    public static function getHourlyRate($userId, $default = 0)
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $rate = self::tinhLuongTheoGio($userId, $now->month, $now->year);
        return $rate > 0 ? $rate : $default;
    }

    // ═══════════════════════════════════════════════════════════════
    // ⭐⭐⭐ CÁC HÀM KIỂM TRA GIỚI HẠN VÀ THỐNG KÊ ⭐⭐⭐
    // ═══════════════════════════════════════════════════════════════

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