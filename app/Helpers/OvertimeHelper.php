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
     * Tính lương tăng ca
     */
    public static function tinhLuongTangCa($userId, $hours, $type = 'ngay_thuong')
    {
        $hourlyRate = self::getHourlyRate($userId);
        if ($hourlyRate <= 0) {
            return 0;
        }

        // Hệ số lương theo loại tăng ca
        $heSo = match ($type) {
            'ngay_thuong' => 1.5,
            'ngay_nghi' => 2.0,
            default => 1.5,
        };

        return round($hours * $hourlyRate * $heSo, 0);
    }

    /**
     * ⭐ KIỂM TRA GIỚI HẠN GIỜ TĂNG CA THEO QUY ĐỊNH
     * 
     * @param int $userId ID nhân viên
     * @param string $ngayTangCa Ngày tăng ca (Y-m-d)
     * @param float $soGioTangCa Số giờ tăng ca đề xuất
     * @param int|null $excludeId Loại trừ ID khi cập nhật
     * @return array ['valid' => bool, 'message' => string, 'details' => array]
     */
    public static function kiemTraGioiHan($userId, $ngayTangCa, $soGioTangCa, $excludeId = null)
    {
        $config = config('overtime.limits');
        $ngay = Carbon::parse($ngayTangCa);
        $details = [];
        
        // 1️⃣ KIỂM TRA GIỚI HẠN NGÀY: Không quá 4 giờ/ngày (50% của 8h)
        if ($soGioTangCa > $config['max_hours_per_day']) {
            return [
                'valid' => false,
                'message' => "❌ Số giờ tăng ca không được vượt quá {$config['max_hours_per_day']} giờ/ngày (tối đa 50% giờ làm việc bình thường).",
                'details' => ['limit' => 'day', 'max' => $config['max_hours_per_day'], 'current' => $soGioTangCa]
            ];
        }
        
        // 2️⃣ KIỂM TRA TỔNG GIỜ LÀM VIỆC TRONG NGÀY (không quá 12 giờ/ngày)
        $tongGioTrongNgay = DangKyTangCa::where('nguoi_dung_id', $userId)
            ->where('ngay_tang_ca', $ngayTangCa)
            ->whereIn('trang_thai', ['da_duyet', 'cho_duyet'])
            ->when($excludeId, function ($query) use ($excludeId) {
                return $query->where('id', '!=', $excludeId);
            })
            ->sum('so_gio_tang_ca');
        
        // Giờ làm việc chính là 8 giờ
        $gioLamChinh = 8;
        $tongGioLamTrongNgay = $gioLamChinh + $tongGioTrongNgay + $soGioTangCa;
        if ($tongGioLamTrongNgay > 12) {
            return [
                'valid' => false,
                'message' => "❌ Tổng giờ làm việc trong ngày không được vượt quá 12 giờ. (Giờ làm chính: {$gioLamChinh}h, Tăng ca hiện tại: {$tongGioTrongNgay}h, Đề xuất: {$soGioTangCa}h)",
                'details' => ['limit' => 'total_day', 'max' => 12, 'current' => $tongGioLamTrongNgay]
            ];
        }
        
        // 3️⃣ KIỂM TRA GIỚI HẠN THÁNG: Không quá 40 giờ/tháng
        $thangHienTai = $ngay->format('Y-m');
        $tongGioThang = DangKyTangCa::where('nguoi_dung_id', $userId)
            ->whereIn('trang_thai', ['da_duyet', 'cho_duyet'])
            ->whereRaw("DATE_FORMAT(ngay_tang_ca, '%Y-%m') = ?", [$thangHienTai])
            ->when($excludeId, function ($query) use ($excludeId) {
                return $query->where('id', '!=', $excludeId);
            })
            ->sum('so_gio_tang_ca');
        
        $tongGioThangMoi = $tongGioThang + $soGioTangCa;
        if ($tongGioThangMoi > $config['max_hours_per_month']) {
            return [
                'valid' => false,
                'message' => "❌ Tổng số giờ tăng ca trong tháng đã vượt quá {$config['max_hours_per_month']} giờ. (Đã đăng ký: {$tongGioThang} giờ, Đề xuất: {$soGioTangCa} giờ)",
                'details' => ['limit' => 'month', 'max' => $config['max_hours_per_month'], 'current' => $tongGioThangMoi, 'used' => $tongGioThang]
            ];
        }
        
        // 4️⃣ KIỂM TRA GIỚI HẠN NĂM: Không quá 200 giờ/năm
        $namHienTai = $ngay->format('Y');
        $tongGioNam = DangKyTangCa::where('nguoi_dung_id', $userId)
            ->whereIn('trang_thai', ['da_duyet', 'cho_duyet'])
            ->whereYear('ngay_tang_ca', $namHienTai)
            ->when($excludeId, function ($query) use ($excludeId) {
                return $query->where('id', '!=', $excludeId);
            })
            ->sum('so_gio_tang_ca');
        
        $tongGioNamMoi = $tongGioNam + $soGioTangCa;
        if ($tongGioNamMoi > $config['max_hours_per_year']) {
            return [
                'valid' => false,
                'message' => "❌ Tổng số giờ tăng ca trong năm đã vượt quá {$config['max_hours_per_year']} giờ. (Đã đăng ký: {$tongGioNam} giờ, Đề xuất: {$soGioTangCa} giờ)",
                'details' => ['limit' => 'year', 'max' => $config['max_hours_per_year'], 'current' => $tongGioNamMoi, 'used' => $tongGioNam]
            ];
        }
        
        return [
            'valid' => true,
            'message' => 'Đơn tăng ca hợp lệ',
            'details' => [
                'day_limit' => $config['max_hours_per_day'],
                'month_limit' => $config['max_hours_per_month'],
                'year_limit' => $config['max_hours_per_year'],
                'day_used' => $tongGioTrongNgay,
                'month_used' => $tongGioThang,
                'year_used' => $tongGioNam,
                'proposed_hours' => $soGioTangCa
            ]
        ];
    }

// Thêm function format thời gian
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

// Cập nhật function thongKeGioTangCa
public static function thongKeGioTangCa($userId)
{
    $today = Carbon::today();
    $month = $today->format('Y-m');
    $year = $today->format('Y');
    $statuses = ['da_duyet', 'cho_duyet'];
    
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
    
    $config = config('overtime.limits');
    
    return [
        'trong_thang' => round($trongThang, 1),
        'trong_nam' => round($trongNam, 1),
        'cho_duyet' => round($choDuyet, 1),
        'hoan_thanh' => round($hoanThanh, 1),
        'limit_month' => $config['max_hours_per_month'],
        'limit_year' => $config['max_hours_per_year'],
        'remaining_month' => max(0, $config['max_hours_per_month'] - $trongThang),
        'remaining_year' => max(0, $config['max_hours_per_year'] - $trongNam),
        // ⭐ THÊM FORMAT HIỂN THỊ
        'trong_thang_text' => self::formatHours($trongThang),
        'trong_nam_text' => self::formatHours($trongNam),
        'remaining_month_text' => self::formatHours(max(0, $config['max_hours_per_month'] - $trongThang)),
        'remaining_year_text' => self::formatHours(max(0, $config['max_hours_per_year'] - $trongNam)),
        'limit_month_text' => self::formatHours($config['max_hours_per_month']),
        'limit_year_text' => self::formatHours($config['max_hours_per_year']),
    ];
}
}