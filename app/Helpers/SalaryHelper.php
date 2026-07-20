<?php
// app/Helpers/SalaryHelper.php

namespace App\Helpers;

use App\Models\NguoiDung;
use App\Models\Luong;

class SalaryHelper
{
    /**
     * Lấy lương cơ bản của nhân viên
     */
    public static function getBaseSalary($userId, $default = 0)
    {
        $user = NguoiDung::find($userId);
        if (!$user) {
            return $default;
        }

        // 1. Từ bảng nguoi_dung
        if ($user->luong_co_ban && $user->luong_co_ban > 0) {
            return $user->luong_co_ban;
        }

        // 2. Từ bảng luong (mới nhất)
        $luong = Luong::where('nguoi_dung_id', $userId)
            ->orderBy('created_at', 'desc')
            ->first();
        if ($luong && $luong->luong_co_ban > 0) {
            return $luong->luong_co_ban;
        }

        // 3. Từ hợp đồng lao động
        $hopDong = $user->hopDongLaoDong()
            ->where('trang_thai_hop_dong', 'hieu_luc')
            ->orderBy('id', 'desc')
            ->first();
        if ($hopDong && $hopDong->luong_co_ban > 0) {
            return $hopDong->luong_co_ban;
        }

        return $default;
    }

    /**
     * Lấy lương theo giờ
     */
    public static function getHourlyRate($userId, $default = 0)
    {
        $user = NguoiDung::find($userId);
        if (!$user) {
            return $default;
        }

        if ($user->luong_theo_gio && $user->luong_theo_gio > 0) {
            return $user->luong_theo_gio;
        }

        $luongCoBan = self::getBaseSalary($userId);
        if ($luongCoBan > 0) {
            return round($luongCoBan / (26 * 8), 0);
        }

        return $default;
    }

    /**
     * Tính lương tăng ca
     */
    public static function calculateOvertimeSalary($userId, $hours, $type = 'ngay_thuong')
    {
        $hourlyRate = self::getHourlyRate($userId);
        if ($hourlyRate <= 0) {
            return 0;
        }

        $heSo = match ($type) {
            'ngay_thuong' => 1.5,
            'ngay_nghi' => 2.0,
            default => 1.5,
        };

        return round($hours * $hourlyRate * $heSo, 0);
    }
}